<?php

class batchProcessor
{
    private $m_objConn;
	private $m_objConnHistory;

    // setup the database connection and session
    public function __construct()
    {
    }

    // clean up the database connection
    public function __destruct()
    {
        dbClose($this->m_objConn);
		dbClose($this->m_objConnHistory);
    }

//================================================================================

	// initialise
	public function initialise($strBatchDB_a)
	{
		// no redirection yet required for batch processes
		updateSessionDB($strBatchDB_a, "", __FUNCTION__);	// temporary, in future it might be a lookup to cater for multiple hosts
		
		// open the db
		if (ENABLE_CLIENTDATABASES == 'TRUE')
		{
			if (getSessionDB(__FUNCTION__) == "client")
			{
				$this->m_objConn = dbOpen(DBCLIENTMAIN_HOSTNAME, DBCLIENTMAIN_LOGIN, DBCLIENTMAIN_PASSWORD, DBCLIENTMAIN_DATABASENAME);
				$this->m_objConnHistory = dbOpen(DBCLIENTHISTORY_HOSTNAME, DBCLIENTHISTORY_LOGIN, DBCLIENTHISTORY_PASSWORD, DBCLIENTHISTORY_DATABASENAME);
			}
			else if (getSessionDB(__FUNCTION__) == "system")
			{
				$this->m_objConn = dbOpen(DBSYSTEMMAIN_HOSTNAME, DBSYSTEMMAIN_LOGIN, DBSYSTEMMAIN_PASSWORD, DBSYSTEMMAIN_DATABASENAME);
				$this->m_objConnHistory = dbOpen(DBSYSTEMHISTORY_HOSTNAME, DBSYSTEMHISTORY_LOGIN, DBSYSTEMHISTORY_PASSWORD, DBSYSTEMHISTORY_DATABASENAME);
			}
		}
		else
		{
			$this->m_objConn = dbOpen(DBSYSTEMMAIN_HOSTNAME, DBSYSTEMMAIN_LOGIN, DBSYSTEMMAIN_PASSWORD, DBSYSTEMMAIN_DATABASENAME);
			$this->m_objConnHistory = dbOpen(DBSYSTEMHISTORY_HOSTNAME, DBSYSTEMHISTORY_LOGIN, DBSYSTEMHISTORY_PASSWORD, DBSYSTEMHISTORY_DATABASENAME);
		}

        if (toBoolean(SESSION_IN_DATABASE)) 
		{
			$objSessionSystem = new SessionManager(DBSYSTEMTEMP_HOSTNAME, DBSYSTEMTEMP_LOGIN, DBSYSTEMTEMP_PASSWORD, DBSYSTEMTEMP_DATABASENAME);
        }
        session_name(SESSION_NAME . 'BATCH');
        session_start();

        // initialise security model
        if (isset($_SESSION[SESSION_SECURITY])) 
		{
            // do nothing
        } 
		else 
		{
            $_SESSION[SESSION_SECURITY] = array();
        }
	}

    // process an action
    public function processBatch($blnProcessSchemaChanges_a, $blnProcessEntityStats_a, $blnBatchJobs_a, $blnEventExpired_a, $blnEventRenewal_a, $blnReminders_a, $blnSendEmails_a, $blnWidgets_a, $blnLogData_a, $blnConfig_a, $blnHousekeeping_a, $blnProcessBuild_a, $blnProcessFiles_a, $blnProcessIntegrationTaskInbound_a, $blnProcessIntegrationTaskOutbound_a, $blnProcessRoster_a)
    {
		$strTableNameSchemaChanges = getTableNameEntity("schemachange", false);

        $blnResult = false;
		$intSchemaChangeCount = 0;
		$intSchemaChangesInRun = 0;

        echo (getSessionDB(__FUNCTION__) . " - started processing...");

        $strError = '';
        try
        {
            $strBatchToken = getGUID();
            $strBatchCookie = 'BatchCookie';
            $strBatchAgent = 'PHP';
            $strBatchHost = 'localhost';
			$strClientDB = getSessionDB(__FUNCTION__);
            $blnResult = userLogin($this->m_objConn, $strBatchToken, $strBatchCookie, BATCH_CLIENT, BATCH_LOGIN, BATCH_PASSWORD, '', 'N', $strBatchAgent, $strBatchHost, "", false, $strClientDB, false, '');
        } catch (PDOException $e) {
            $strError = 'could not connect - ' . $e->getMessage();
        }

        if (($blnResult == true) && ($this->m_objConn != null)) 
		{

			if (dependencies('esb/esbRegisterBroadcaster'))
			{
				$strBatchDeviceID = $_SESSION['server_deviceid'];

				// fetch the batch client
				$strBatchClientID = getBatchClientID($this->m_objConn);

				// register the batch process esb broadcasters
				esbRegisterBroadcaster($this->m_objConn, $strBatchClientID, $strBatchDeviceID, 'messages');
                esbRegisterBroadcaster($this->m_objConn, $strBatchClientID, $strBatchDeviceID, 'dashboard');
			}
			
			// check if there are pending schema changes
			$strSQL = "select count(*) returnvalue from ~TABLENAMESCHEMACHANGES~ where g523b5f4a_fc16_4f51_ad0c_c1cee86223bd_isdone = 'N'";
			$strSQL = str_replace("~TABLENAMESCHEMACHANGES~", ff($strTableNameSchemaChanges), $strSQL);
			$intSchemaChangeCount = dbReadValue($this->m_objConn, $strSQL, __FUNCTION__);
			$intSchemaChangesInRun = 0;
			$intSchemaChangeCount = 0;
			
			if ($blnProcessSchemaChanges_a)
			{
				if (dependencies('batch/processes/processSchemaChanges'))
				{
					$intSchemaChangesInRun = processSchemaChanges($this->m_objConn);
				}
			}

			if ($intSchemaChangeCount == 0)
			{
				if ($blnWidgets_a)
				{
					if (dependencies('batch/processes/processBatchWidgets'))
					{
						processBatchWidgets($this->m_objConn, $strBatchClientID, $strBatchDeviceID);

					}
				}

				if ($blnBatchJobs_a)
				{
					if (dependencies('batch/processes/processBatchJobs'))
					{
						// note, no return value for batchjob processing as the errors are actually logged within the batchjobs table
						processBatchJobs($this->m_objConn);
					}
				}

				if ($blnReminders_a)
				{
					if (dependencies('batch/processes/processReminders'))
					{
						// note, no return value for reminder processing as the errors are actually logged within the batchjobs table
						processReminders($this->m_objConn);
					}
				}

				if ($blnLogData_a)
				{
					if (dependencies('batch/processes/processLogData'))
					{
						// note, no return value for logdata processing as the errors are actually logged within the logdata table
						processLogData($this->m_objConn);
					}
				}

				if ($blnSendEmails_a)
				{
					if (dependencies('batch/processes/processSendEmails'))
					{
						// note, no return value for email processing as the errors are actually logged within the batchjobs table
						processSendEmails($this->m_objConn);
					}
				}

				if ($blnProcessEntityStats_a)
				{
					if (dependencies('batch/processes/processEntityStats'))
					{
						$intEntityStatsInRun = processEntityStats($this->m_objConn, $this->m_objConnHistory);
					}
				}

				if ($blnConfig_a)
				{
					if (dependencies('batch/processes/processConfig'))
					{
						processConfig($this->m_objConn);
					}
                }
                
				if ($blnEventExpired_a)
				{
					if (dependencies('batch/processes/processClientProductExpired')) 
					{
						echo ("client expired...");
						processClientProductExpired($this->m_objConn); 
					}
				}
				
				if ($blnEventRenewal_a)
				{
					if (dependencies('batch/processes/processClientProductRenewal')) 
					{
						echo ("client renewal...");
						processClientProductRenewal($this->m_objConn); 
					}
				}

                if ($blnHousekeeping_a)
				{ 
					if (dependencies('batch/processes/processGeneralHousekeeping'))
					{
						processGeneralHousekeeping($this->m_objConn);
					}
                }
                
                if ($blnProcessBuild_a)
				{ 
					if (dependencies('batch/processes/processBatchBuild'))
					{
						processBatchBuild($this->m_objConn);
					}
                }

				if ($blnProcessFiles_a)
				{
					if (dependencies('batch/processes/processFiles'))
					{
						processFiles($this->m_objConn);
					}
				}
                
                if ($blnProcessIntegrationTaskInbound_a)
                { 
                    if (dependencies('batch/processes/processIntegrationTaskInbound'))
                    {
                        processIntegrationTaskInbound($this->m_objConn);
                    }
                }

                if ($blnProcessIntegrationTaskOutbound_a)
                { 
                    if (dependencies('batch/processes/processIntegrationTaskOutbound'))
                    {
                        processIntegrationTaskOutbound($this->m_objConn);
                    }
                }

                if ($blnProcessRoster_a)
                { 
                    if (dependencies('batch/processes/processBatchRoster'))
                    {
                        processBatchRoster($this->m_objConn);
                    }
                }
			}
            else
            {
                if (!$blnProcessSchemaChanges_a)
                {
                    $strError = "There are schema process changes. Please run batch schema process...";
                }
            }
            
            userLogout();
        } 
		else 
		{
            $strError = 'batch process cannot login';
        }

		if ($blnProcessSchemaChanges_a)
		{
			if (($intSchemaChangeCount > 0) && ($intSchemaChangesInRun > 0))
			{
				if ($strError == '') 
				{
					echo ("completed successfully...");
				} 
				else 
				{
					echo ("completed with errors: " . $strError . "...");
				}
			}
			else
			{
				if ($strError == '') 
				{
					echo ("check pending schema changes for errors...");
				} 
				else 
				{
					echo ("completed with errors: " . $strError . "...");
				}
			}
		}
		else
		{
			if ($intSchemaChangeCount > 0)
			{
				echo ("batch skipped pending schema changes...");
			}
			else
			{
				if ($strError == '') 
				{
					echo ("completed successfully...");
				} 
				else 
				{
					echo ("completed with errors: " . $strError . "...");
				}
			}
		}
		
    }
}
