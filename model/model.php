<?php

namespace model;

use mysqli;

class Model
{
    protected $connection;
    protected $table;
    protected $result;

    /**
     * Costruttore della classe.
     * Istanzia un nuovo oggetto di connessione mysqli.
     */
    public function __construct()
    {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }

    /**
     * Distruttore della classe.
     * Chiude la connessione con il database.
     */
    public function __destruct()
    {
        $this->connection->close();
    }

    /**
     * Esegue una query per recuperare tutti i record dalla tabella corrente.
     *
     * @return object Restituisce l'istanza corrente della classe per consentire la concatenazione dei metodi.
     */
    public function get()
    {
        $query = "SELECT * FROM $this->table";
        $this->result = $this->connection->query($query);
        return $this;
    }

    /**
     * Esegue una query per trovare un record specifico in base all'ID fornito.
     *
     * @param int $id L'ID del record da cercare.
     * @return object Restituisce l'istanza corrente della classe per consentire la concatenazione dei metodi.
     */
    public function find($id)
    {
        $query = "SELECT * FROM $this->table WHERE id = $id";
        $this->result = $this->connection->query($query);
        return $this;
    }

    /**
     * Esegue una query per trovare i record che soddisfano una condizione specificata.
     *
     * @param string $column Il nome della colonna su cui eseguire la condizione.
     * @param mixed $value Il valore che deve corrispondere alla colonna specificata.
     * @return object Restituisce l'istanza corrente della classe per consentire la concatenazione dei metodi.
     */
    public function where($column, $value)
    {
        $query = "SELECT * FROM $this->table WHERE $column = '$value'";
        $this->result = $this->connection->query($query);
        return $this;
    }

    /**
     * Restituisce il primo record restituito dalla query.
     *
     * @return array|false Restituisce un array associativo contenente il primo record, o FALSE se non ci sono record.
     */
    public function first()
    {
        return $this->result->fetch_assoc();
    }

    /**
     * Restituisce tutti i record restituiti dalla query.
     *
     * @return array Restituisce un array di array associativi contenenti tutti i record restituiti.
     */
    public function all()
    {
        return $this->result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Esegue una query di inserimento nella tabella corrente e restituisce l'ID del record appena inserito.
     *
     * @param array $data Un array associativo dei dati da inserire, con i nomi delle colonne come chiavi e i valori dei dati come valori.
     * @return int L'ID del record appena inserito.
     */
    public function insert($data)
    {
        $columns = implode(', ', array_keys($data));
        $values = implode("', '", array_values($data));
        $query = "INSERT INTO $this->table ($columns) VALUES ('$values')";
        $this->connection->query($query);
        return $this->connection->insert_id;
    }

    /**
     * Esegue una query di aggiornamento nella tabella corrente.
     *
     * @param array $data Un array associativo dei dati da aggiornare, con i nomi delle colonne come chiavi e i nuovi valori dei dati come valori.
     * @param int $id L'ID del record da aggiornare.
     * @return object Restituisce l'istanza corrente della classe per consentire la concatenazione dei metodi.
     */
    public function update($data, $id)
    {
        $set = '';
        foreach ($data as $key => $value) {
            $set .= "$key = '$value', ";
        }
        $set = rtrim($set, ', ');
        $query = "UPDATE $this->table SET $set WHERE id = $id";
        return $this->connection->query($query);
    }

    /**
     * Esegue una query di eliminazione nella tabella corrente.
     *
     * @param int $id L'ID del record da eliminare.
     * @return object Restituisce l'istanza corrente della classe per consentire la concatenazione dei metodi.
     */
    public function delete($id)
    {
        $query = "DELETE FROM $this->table WHERE id = $id";
        $this->connection->query($query);
        return $this;
    }

    /**
     * Esegue una query personalizzata sulla base di dati.
     *
     * @param string $query La query SQL personalizzata da eseguire.
     * @return object Restituisce l'istanza corrente della classe per consentire la concatenazione dei metodi.
     */
    public function query($query)
    {
        $this->result = $this->connection->query($query);
        return $this;
    }
}
