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
 * \defgroup    Prospection    Prospection module
 * \brief       Prospection module descriptor.
 *
 * Put detailed description here.
 */

/**
 * \file        core/modules/modProspection.class.php
 * \ingroup     Prospection
 * \brief       Example module description and activation file.
 *
 * Put detailed description here.
 */

include_once DOL_DOCUMENT_ROOT .'/core/modules/DolibarrModules.class.php';

// The class name should start with a lower case mod for Dolibarr to pick it up
// so we ignore the Squiz.Classes.ValidClassName.NotCamelCaps rule.
// @codingStandardsIgnoreStart
/**
 * Description and activation class for module Prospection
 */
class modProspection extends DolibarrModules
{
	/** @var DoliDB Database handler */
	public $db;
	public $numero = 500130;
	public $rights_class = 'prospection';
	public $family = 'FM Medical';
	public $module_position = 100;
	public $familyinfo = array();

	/** @var string Module name */
	public $name = "Prospection";

	/** @var string Module short description */
	public $description = "Gestion avancée de la prospection";

	/** @var string Module long description */
	public $descriptionlong = "";
	public $editor_name = "FM Medical";
	public $editor_url = "http://www.fournisseur-medical.com";

	/**
	 * @var string Module version string
	 * Special values to hide the module behind MAIN_FEATURES_LEVEL: development, experimental
	 * @see https://semver.org
	 */
	public $version = '1.0.0';

	/** @var string Key used in llx_const table to save module status enabled/disabled */
	public $const_name = 'MAIN_MODULE_PROSPECTION';
	public $picto = 'prospection@prospection';

	/** @var array Define module parts */
	public $module_parts = array(
		'triggers' => false,
		'login' => false,
		'substitutions' => false,
		'menus' => false,
		'theme' => false,
		'tpl' => false,
		'barcode' => false,
		'models' => false,
		'css' => array(
			
		),
		'js' => array(
		),
		'hooks' => array(),
		'dir' => array(),
		'workflow' => array(),
	);

	/** @var string Data directories to create when module is enabled */
	public $dirs = array(
		'/prospection/temp'
	);
	//public $config_page_url = 'setup.php@prospection';

	/** @var bool Control module visibility */
	public $hidden = false;

	/** @var string[] List of class names of modules to enable when this one is enabled */
	public $depends = array();

	/** @var string[] List of class names of modules to disable when this one is disabled */
	public $requiredby = array();

	/** @var string List of class names of modules this module conflicts with */
	public $conflictwith = array();
	public $phpmin = array(5, 3);
	public $need_dolibarr_version = array(3, 2);
	public $langfiles = array('prospection@prospection');
	public $const = array();
	public $tabs = array();
	public $dictionaries = array();
	public $boxes = array();
	public $cronjobs = array();
	public $rights = array();
	public $menu = array();
	public $export_code = array();
	public $export_label = array();
	public $export_enabled = array();
	public $export_permission = array();
	public $export_fields_array = array();
	public $export_entities_array = array();
	public $export_sql_start = array();
	public $export_sql_end = array();
	public $core_enabled = false;

	// @codingStandardsIgnoreEnd
	/**
	 * Constructor. Define names, constants, directories, boxes, permissions
	 *
	 * @param DoliDB $db Database handler
	 */
	public function __construct($db)
	{
		global $langs, $conf;

		// DolibarrModules is abstract in Dolibarr < 3.8
		if (is_callable('parent::__construct')) {
			parent::__construct($db);
		} else {
			global $db;
			$this->db = $db;
		}

		// Dictionaries
		if (! isset($conf->prospection->enabled)) {
			$conf->prospection=new stdClass();
			$conf->prospection->enabled = 0;
		}

		// Permissions
		//$r = 0;
		// Add here list of permission defined by
		// an id, a label, a boolean and two constant strings.
		// Example:
		//// Permission id (must not be already used)
		//$this->rights[$r][0] = 2000;
		//// Permission label
		//$this->rights[$r][1] = 'Permision label';
		//// Permission by default for new user (0/1)
		//$this->rights[$r][3] = 1;
		//// In php code, permission will be checked by test
		//// if ($user->rights->permkey->level1->level2)
		//$this->rights[$r][4] = 'level1';
		//// In php code, permission will be checked by test
		//// if ($user->rights->permkey->level1->level2)
		//$this->rights[$r][5] = 'level2';
		//$r++;
		// Main menu entries

		// Menu entries
		// Example to declare a new Top Menu entry and its Left menu entry:
		// $this->menu[]=array(
		// 		// Use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy'
		// 		'fk_menu'=>'fk_mainmenu=products,fk_leftmenu=sendings',
		// 		// This is a Left menu entry
		// 		'type'=>'left',
		// 		// Menu's title. FIXME: use a translation key
		// 		'titre'=>'Statistiques logistiques',
		// 		// This menu's mainmenu ID
		// 		'mainmenu'=>'products',
		// 		// This menu's leftmenu ID
		// 		'leftmenu'=>'sendings',
		// 		'url'=>'/custom/prospection/index.php',
		// 		// Lang file to use (without .lang) by module.
		// 		// File must be in langs/code_CODE/ directory.
		// 		'langs'=>'mylangfile',
		// 		'position'=>5000,
		// 		// Define condition to show or hide menu entry.
		// 		// Use '$conf->expeditionmailer->enabled' if entry must be visible if module is enabled.
		// 		// Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
		// 		'enabled'=>'$conf->prospection->enabled',
		// 		// Use 'perms'=>'$user->rights->expeditionmailer->level1->level2'
		// 		// if you want your menu with a permission rules
		// 		'perms'=>'1',
		// 		'target'=>'',
		// 		// 0=Menu for internal users, 1=external users, 2=both
		// 		'user'=>2
		// );
	}

	public function init($options = '')
	{
		$sql = array(
			array('sql' => "INSERT INTO " . MAIN_DB_PREFIX . "c_stcomm (id,code, libelle, active) VALUES" .
				" (10, 'FM_FRSTRDR', '1ere commande (0 contact)', '1'),".
				" (11, 'FM_FRSTCNTCT', '1er appel/mail', '1'),".
				" (12, 'FM_SNDCNTCT', '2nd appel/mail (relance)', '1'),".
				" (13, 'FM_SUCCSRDR', 'Commande suite relance', '1'),".
				" (14, 'FM_PROPIP', 'Devis ou liste en cours', '1'),".
				" (15, 'FM_PROPFLD', 'Devis négatif', '1'),".
				" (16, 'FM_LOST', 'Consultation perdue', '1'),".
				" (17, 'FM_DND', 'Ne pas déranger', '1')"
			),
			array('sql' => "INSERT INTO " . MAIN_DB_PREFIX . "c_actioncomm (id, code, type, libelle, module, active, position, color)" .
				" VALUES (100, 'FM_PROSPECTION', 'user', 'Prospection', 'prospection', 1, 0, '#0EAF9E')"
			)
		);

		$result = $this->loadTables();
		
		return $this->_init($sql, $options);
	}

	private function loadTables()
	{
		return $this->_load_tables('/prospection/sql/');
	}

	public function remove($options = '')
	{
		$sql = array(
			"DELETE FROM " . MAIN_DB_PREFIX . "c_stcomm WHERE code LIKE 'FM_%'",
			"DELETE FROM " . MAIN_DB_PREFIX . "c_actioncomm WHERE code LIKE 'FM_%'"
		);

		return $this->_remove($sql, $options);
	}
}
