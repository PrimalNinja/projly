<?php

class dbConnection
{
    private $m_objPDO;
	private $m_strDatabaseName;
    private $m_strLogFile;
    private $m_blnUsed;

    public function __construct()
    {
        $this->m_blnUsed = false;
    }

    public function PDO()
    {
        return $this->m_objPDO;
    }

    public function logFile()
    {
        return $this->m_strLogFile;
    }

    public function getDatabaseName()
    {
        return $this->m_strDatabaseName;
    }

    public function isUsed()
    {
        return $this->m_blnUsed;
    }

    public function setDatabaseName($strDatabasename_a)
    {
        $this->m_strDatabaseName = $strDatabasename_a;
    }

    public function setUsed()
    {
        $this->m_blnUsed = true;
    }
}
