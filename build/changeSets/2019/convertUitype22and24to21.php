<?php
/*************************************************************************************************
 * Copyright 2018 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
* Licensed under the vtiger CRM Public License Version 1.1 (the "License"); you may not use this
* file except in compliance with the License. You can redistribute it and/or modify it
* under the terms of the License. JPL TSolucio, S.L. reserves all rights not expressly
* granted by the License. coreBOS distributed by JPL TSolucio S.L. is distributed in
* the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
* warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Unless required by
* applicable law or agreed to in writing, software distributed under the License is
* distributed on an "AS IS" BASIS, WITHOUT ANY WARRANTIES OR CONDITIONS OF ANY KIND,
* either express or implied. See the License for the specific language governing
* permissions and limitations under the License. You may obtain a copy of the License
* at <http://corebos.org/documentation/doku.php?id=en:devel:vpl11>
*************************************************************************************************/

class convertUitype22and24to21 extends cbupdaterWorker {

	public function applyChange() {
		global $adb;
		if ($this->hasError()) {
			$this->sendError();
		}
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
			$this->ExecuteQuery("UPDATE vtiger_field SET uitype =21 WHERE (uitype=? OR uitype=?)", array('22','24'));
			$rsws22 = $adb->pquery('select fieldtypeid from vtiger_ws_fieldtype where uitype=?', array('22'));
			$fldtid22 = $adb->query_result($rsws22, 0, 0);
			$rsws24 = $adb->pquery('select fieldtypeid from vtiger_ws_fieldtype where uitype=?', array('24'));
			$fldtid24 = $adb->query_result($rsws24, 0, 0);
			$this->ExecuteQuery("DELETE FROM `vtiger_ws_fieldtype` WHERE vtiger_ws_fieldtype.uitype=?;", array('22'));
			$this->ExecuteQuery("DELETE FROM `vtiger_ws_fieldtype` WHERE vtiger_ws_fieldtype.uitype=?;", array('24'));
			$this->ExecuteQuery("DELETE FROM `vtiger_ws_referencetype` WHERE `vtiger_ws_referencetype`.`fieldtypeid` = ?", array($fldtid22));
			$this->ExecuteQuery("DELETE FROM `vtiger_ws_referencetype` WHERE `vtiger_ws_referencetype`.`fieldtypeid` = ?", array($fldtid24));

			$this->sendMsg('Changeset '.get_class($this).' applied!');
			$this->markApplied(false);
		}
		$this->finishExecution();
	}
}