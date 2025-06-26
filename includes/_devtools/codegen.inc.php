/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
	/* This includes library file is used by the codegen.cli and codegen.phpexe scripts
	 * to simply fire up and run the QCodeGen object, itself.
	 */

	// Call the CLI prepend.inc.php
//	require('cli_prepend.inc.php');

	// Include the QCodeGen class library
	require(__QCUBED__. '/codegen/QCodeGen.class.php');

	// code generators
	include (__QCUBED_CORE__ . '/codegen/controls/_class_paths.inc.php');



function PrintInstructions() {
		global $strCommandName;
		print('QCubed Code Generator (Command Line Interface) - ' . QCUBED_VERSION . '
Copyright (c) 2001 - 2009, QuasIdea Development, LLC, QCubed Project
This program is free software with ABSOLUTELY NO WARRANTY; you may
redistribute it under the terms of The MIT License.

Usage: ' . $strCommandName . ' CODEGEN_SETTINGS

Where CODEGEN_SETTINGS is the absolute filepath of the codegen_settings.xml
file, containing the code generator settings.

For more information, please go to http://qcu.be
');
		exit();
	}

	$settingsFile = __CONFIGURATION__ . '/codegen_settings.xml';
	if ($_SERVER['argc'] >= 2)
		$settingsFile = $_SERVER['argv'][1];

	if (!is_file($settingsFile)) {
		PrintInstructions();
	}

	/////////////////////
	// Run Code Gen
	QCodeGen::Run($settingsFile);
	/////////////////////


	if ($strErrors = QCodeGen::$RootErrors) {
		printf("The following ROOT ERRORS were reported:\r\n%s\r\n\r\n", $strErrors);
	} else {
		printf("CodeGen settings (as evaluted from %s):\r\n%s\r\n\r\n", $settingsFile, QCodeGen::GetSettingsXml());
	}

	foreach (QCodeGen::$CodeGenArray as $objCodeGen) {
		printf("%s\r\n---------------------------------------------------------------------\r\n", $objCodeGen->GetTitle());
		printf("%s\r\n", $objCodeGen->GetReportLabel());
		printf("%s\r\n", $objCodeGen->GenerateAll());
		if ($strErrors = $objCodeGen->Errors)
			printf("The following errors were reported:\r\n%s\r\n", $strErrors);
		print("\r\n");
	}

	foreach (QCodeGen::GenerateAggregate() as $strMessage) {
		printf("%s\r\n\r\n", $strMessage);
	}