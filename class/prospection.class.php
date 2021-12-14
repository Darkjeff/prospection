<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) <year>  <name of author>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    class/myclass.class.php
 * \ingroup mymodule
 * \brief   Example CRUD (Create/Read/Update/Delete) class.
 *
 * Put detailed description here.
 */

/**
 * Put your class' description here
 */
class Prospection // extends CommonObject
{
	private $db;

	const TABLE_NAME = "prospection";

	public $error;

	public $errors = array();

	/**
	 * Constructor
	 *
	 * @param DoliDb $db Database handler
	 */
	public function __construct($db)
	{
		$this->db = $db;

		return 1;
	}

	/**
	 * Create object into database
	 *
	 * @param User $user User that create
	 * @param int $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int <0 if KO, Id of created object if OK
	 */
	public function create($user, $notrigger = 0)
	{
		global $conf, $langs;
		$error = 0;

		$sql = "INSERT INTO " . MAIN_DB_PREFIX . self::TABLE_NAME."(";
		$sql .= 'fk_soc,';
		$sql .= 'comment,';
		$sql .= 'date_relance,';
		$sql .= 'date_creation,';
		$sql .= 'tms';
		$sql .= ") VALUES (";
		$sql .= $this->fk_soc.',';
		$sql .= '"'.addslashes($this->comment).'",';
		$sql .= $this->date_relance.',';
		$sql .= '"'.date('Y-m-d H:i:s').'",';
		$sql .= '"'.date('Y-m-d H:i:s').'"';
		$sql .= ")";

		$this->db->begin();

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if (!$resql) {
			$error++;
			$this->errors[] = "Error " . $this->db->lasterror();
		}

		if (!$error) {
			$this->id = $this->db->last_insert_id(MAIN_DB_PREFIX . self::TABLE_NAME);
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error .= ($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();

			return -1 * $error;
		} else {
			$this->db->commit();

			return $this->id;
		}
	}

	public function fetch($id)
	{
		global $langs;
		$sql = "SELECT * ";
		$sql .= " FROM " . MAIN_DB_PREFIX . self::TABLE_NAME;
		$sql .= " WHERE rowid = " . $id;

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		if ($sql)
			$resql = $this->db->query($sql);
		if ($resql) {
			if ($this->db->num_rows($resql)) {
				$obj = $this->db->fetch_object($resql);

				$this->rowid = $obj->rowid;
				$this->fk_soc = $obj->fk_soc;
				$this->comment = $obj->comment;
				$this->date_relance = $obj->date_relance;
				$this->date_creation = $obj->date_creation;
				$this->tms = $obj->tms;
			}
			$this->db->free($resql);

			return $this->rowid;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);

			return -1;
		}
	}

	public function fetchBySoc($fk_soc)
	{
		global $langs;
		$sql = "SELECT * ";
		$sql .= " FROM " . MAIN_DB_PREFIX . self::TABLE_NAME;
		$sql .= " WHERE fk_soc = " . $fk_soc;

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		if ($sql)
			$resql = $this->db->query($sql);
		if ($resql) {
			if ($this->db->num_rows($resql)) {
				$obj = $this->db->fetch_object($resql);

				$this->rowid = $obj->rowid;
			}
			$this->db->free($resql);

			return $this->rowid;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);

			return -1;
		}
	}

	public function update()
	{
		$error = 0;
		$now = date('Y-m-d H:i:s');

		$sql = "UPDATE " . MAIN_DB_PREFIX . self::TABLE_NAME;
		$sql .= " SET ".$this->field." = '" . $this->value."'";
		$sql .= " ,tms = '".$now."'";
        $sql .= " WHERE rowid = " . $this->rowid;

		$this->db->begin();

		dol_syslog ( __METHOD__ . " sql=" . $sql, LOG_DEBUG );
		$resql = $this->db->query ( $sql );
		if ($resql) {
			if ($this->db->num_rows ( $resql )) {
				$obj = $this->db->fetch_object ( $resql );
			}
            $this->db->free ( $resql );
            $this->db->commit();

			return 1;
		} else {
			$this->error = "Error " . $this->db->lasterror ();
            dol_syslog ( __METHOD__ . " " . $this->error, LOG_ERR );
            $this->db->rollback();

			return - 1;
		}
    }

	public function updateSociete()
	{
		$error = 0;

		$sql = "UPDATE " . MAIN_DB_PREFIX .'societe';
		$sql .= " SET ".$this->field." = '" . $this->value."'";
        $sql .= " WHERE rowid = " . $this->rowid;

		$this->db->begin();

		dol_syslog ( __METHOD__ . " sql=" . $sql, LOG_DEBUG );
		$resql = $this->db->query ( $sql );
		if ($resql) {
			if ($this->db->num_rows ( $resql )) {
				$obj = $this->db->fetch_object ( $resql );
			}
            $this->db->free ( $resql );
            $this->db->commit();

			return 1;
		} else {
			$this->error = "Error " . $this->db->lasterror ();
            dol_syslog ( __METHOD__ . " " . $this->error, LOG_ERR );
            $this->db->rollback();

			return - 1;
		}
    }

	public function delete()
	{
		global $langs;
		$sql = "DELETE FROM " . MAIN_DB_PREFIX . self::TABLE_NAME;
		$sql .= " WHERE rowid = " . $this->rowid;

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql) {
			if ($this->db->num_rows($resql)) {
				$obj = $this->db->fetch_object($resql);
			}
			$this->db->free($resql);

			return 1;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);

			return -1;
		}
	}

	/**
	 * Create object into database if not exist
	 *
	 * @param User $user User that create
	 * @param int $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int <0 if KO, Id of created object if OK
	 */
	public function initLine()
	{
		global $conf, $langs;
		$error = 0;

		$sql = "INSERT INTO " . MAIN_DB_PREFIX . self::TABLE_NAME."(";
		$sql .= " fk_soc,";
		$sql .= " date_creation,";
		$sql .= " tms";
		$sql .= ") VALUES (";
		$sql .= " '" . $this->fk_soc . "',";
		$sql .= " '" . date('Y-m-d H:i:s') . "',";
		$sql .= " '" . date('Y-m-d H:i:s') . "'";
		$sql .= ")";

		$this->db->begin();

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if (!$resql) {
			$error++;
			$this->errors[] = "Error " . $this->db->lasterror();
		}

		if (!$error) {
			$this->rowid = $this->db->last_insert_id(MAIN_DB_PREFIX . self::TABLE_NAME);
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error .= ($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();

			return -1 * $error;
		} else {
			$this->db->commit();

			return $this->rowid;
		}
	}

	public function selectProspectionStatus($currentStatus, $prospectionStatus)
	{
		$html = '<select class="fillable select-prospection-status" data-id="'.$this->fk_soc.'" style="width: 150px">';
		$html.= "<option></option>";
		foreach($prospectionStatus as $key => $value){
			$html.="<option value='$key' ".($key==$currentStatus?"selected":"").">".$this->getStatusLib($value['code'], $value['libelle'])."</option>";
		}
		$html.= '</select>';

		return $html;
	}

	public function getProspectionStatus()
	{
		$sql = "SELECT * ";
		$sql .= " FROM " . MAIN_DB_PREFIX . "c_stcomm";

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql) {
			if ($num = $this->db->num_rows($resql)) {
				$i=0;
				while($i<$num){
					$obj = $this->db->fetch_object($resql);
					$this->prospectionstatus['id'] = $obj->id;
					$this->prospectionstatus['code'] = $obj->code;
					$this->prospectionstatus['libelle'] = $obj->libelle;
					$this->prospectionstatus['picto'] = $obj->picto;
					$this->prospectionstatus['active'] = $obj->active;
					$prospectionStatus[$this->prospectionstatus['id']] = $this->prospectionstatus;
					$i++;
				}
			}
			$this->db->free($resql);

			return $prospectionStatus;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);

			return -1;
		}
	}

	private function getStatusLib($status, $libelle){
		global $langs;

		if ($status == 'ST_NO') {
			return $langs->trans("StatusProspect-1");
		} elseif ($status == 'ST_NEVER') {
			return $langs->trans("StatusProspect0");
		} elseif ($status == 'ST_TODO') {
			return $langs->trans("StatusProspect1");
		} elseif ($status == 'ST_PEND') {
			return $langs->trans("StatusProspect2");
		} elseif ($status == 'ST_DONE') {
			return $langs->trans("StatusProspect3");
		} else {
			return $libelle;
		}
	}

	public function addEvent(){
		global $conf, $langs;
		$error = 0;

		$ref = $this->fk_soc.'Pr'.date('YmdHis');

		$sql = "INSERT INTO ".MAIN_DB_PREFIX."actioncomm";
        $sql.= "(datec, datep, datep2,";
        $sql.= "durationp,";
        $sql.= "fk_action,";
        $sql.= "priority,";
        $sql.= "ref,";
        $sql.= "code,";
        $sql.= "fk_soc,";
        $sql.= "fk_user_author,";
		$sql.= "fk_user_action,";
        $sql.= "label,fulldayevent,";
        $sql.= "transparency,";
        $sql.= "percent,";
        $sql.= "entity,";
        $sql.= "note";
        $sql.= ") VALUES (";
        $sql.= "'".date('Y-m-d H:i:s')."', '".$this->date_creation."','".$this->date_creation."',";
        $sql.= "NULL,";
        $sql.= "100,";
        $sql.= "0,";
        $sql.= "'".$ref."',";
        $sql.= "'FM_PROSPECT',";
        $sql.= $this->fk_soc.",";
        $sql.= $this->userid.",";
        $sql.= $this->userid.",";
        $sql.= "'".$this->db->escape(dol_trunc(trim($this->comment),250))."','0',";
        $sql.= "0,";
        $sql.= "'-1',";
        $sql.= $conf->entity.",";
        $sql.= "'".dol_htmlcleanlastbr(trim($this->comment))."'";
        $sql.= ")";

		echo $sql;

		$this->db->begin();

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if (!$resql) {
			$error++;
			$this->errors[] = "Error " . $this->db->lasterror();
		}

		if (!$error) {
			$this->rowid = $this->db->last_insert_id(MAIN_DB_PREFIX . "actioncomm");

			$sql2= "UPDATE ".MAIN_DB_PREFIX."actioncomm SET ref=id WHERE id=".$this->rowid;

			dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
			$resql2 = $this->db->query($sql2);
			if (!$resql2) {
				$error++;
				$this->errors[] = "Error " . $this->db->lasterror();
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error .= ($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();

			return -1 * $error;
		} else {
			$this->db->commit();

			return $this->rowid;
		}
	}

	public function getLastEvents(){
		global $langs;
		$sql = "SELECT id, datec, label, note";
		$sql .= " FROM " . MAIN_DB_PREFIX . "actioncomm";
		$sql .= " WHERE fk_soc=".$this->fk_soc;
		$sql .= " ORDER BY datec DESC";
		$sql .= " LIMIT 20";

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql) {
			if ($num = $this->db->num_rows($resql)) {
				$i=0;
				while($i<$num){
					$obj = $this->db->fetch_object($resql);
					$this->event['id'] = $obj->id;
					$this->event['datec'] = $obj->datec;
					$this->event['label'] = $obj->label;
					$this->event['note'] = $obj->note;
					$events[$this->event['id']] = $this->event;
					$i++;
				}
			}
			$this->db->free($resql);

			return $events;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);

			return -1;
		}
	}
}
