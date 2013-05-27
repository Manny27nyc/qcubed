<?php
	if (!defined('__PREPEND_INCLUDED__')) {
		// Ensure prepend.inc is only executed once
		define('__PREPEND_INCLUDED__', 1);


		///////////////////////////////////
		// Define Server-specific constants
		///////////////////////////////////	
		/*
		 * This assumes that the configuration include file is in the same directory
		 * as this prepend include file.  For security reasons, you can feel free
		 * to move the configuration file anywhere you want.  But be sure to provide
		 * a relative or absolute path to the file.
		 */
		if (file_exists(dirname(__FILE__) . '/configuration.inc.php')) {
			require(dirname(__FILE__) . '/configuration.inc.php');
		}
		else {
			// The minimal constants set to work
			define ('__DOCROOT__', dirname(__FILE__) . '/../..');
			define ('__INCLUDES__', dirname(__FILE__) . '/..');
			define ('__QCUBED__', __INCLUDES__ . '/qcubed');
			define ('__PLUGINS__', __QCUBED__ . '/plugins');
			define ('__QCUBED_CORE__', __INCLUDES__ . '/qcubed/_core');
			define ('__APP_INCLUDES__', __INCLUDES__ . '/app_includes');
			define ('__MODEL__', __INCLUDES__ . '/model' );
			define ('__MODEL_GEN__', __MODEL__ . '/generated' );
		}


		//////////////////////////////
		// Include the QCubed Framework
		//////////////////////////////
		require(__QCUBED_CORE__ . '/framework/DisableMagicQuotes.inc.php');
		require(__QCUBED_CORE__ . '/qcubed.inc.php');
		require(__APP_INCLUDES__ . '/app_includes.inc.php');

		//////////////////////////////
		// Include the composer's libraries autoload script
		//////////////////////////////
		require_once __INCLUDES__ . '/vendor/autoload.php';
		///////////////////////////////
		// Define the Application Class
		///////////////////////////////
		/**
		 * The Application class is an abstract class that statically provides
		 * information and global utilities for the entire web application.
		 *
		 * Custom constants for this webapp, as well as global variables and global
		 * methods should be declared in this abstract class (declared statically).
		 *
		 * This Application class should extend from the ApplicationBase class in
		 * the framework.
		 */
		abstract class QApplication extends QApplicationBase {
			/**
			 * This is called by the PHP5 Autoloader.  This method overrides the
			 * one in ApplicationBase.
			 *
			 * @return void
			 */
			public static function Autoload($strClassName) {
				// First use the QCubed Autoloader
				if (!parent::Autoload($strClassName)) {
					// TODO: Run any custom autoloading functionality (if any) here...
				}
			}

			////////////////////////////
			// QApplication Customizations (e.g. EncodingType, etc.)
			////////////////////////////
			public static $EncodingType = __QAPPLICATION_ENCODING_TYPE__;

			public static $CountryCode = 'ru';
			public static $LanguageCode = 'ru';

			////////////////////////////
			// Additional Static Methods
			////////////////////////////
			// TODO: Define any other custom global WebApplication functions (if any) here...
			
			/**
			 * Сохраняет все переданные объекты в единой транзакции: или все сохраняются, или не сохраняется ни один.
			 * @param array $arrObjects Объекты, которые нужно сохранить в единой транзакции:
			 * или все сохраняются, или не сохраняется ни один.
			 * @param string $strCustomSql SQL-инструкция, которая должна быть выполнена сразу по входу в транзакцию.
			 * Например, чтобы залочить таблицу.
			 * @return void
			 */
			public static function SaveObjects($arrObjects, $strCustomSql = null) {
				if (!$arrObjects) {
					return;
				}
				try {
					QApplication::$Database[1]->TransactionBegin();
					if ($strCustomSql) {
						QApplication::$Database[1]->NonQuery($strCustomSql);
					}
					
					if (is_array($arrObjects)) foreach($arrObjects as $obj) {
						$obj->Save();
					}
					else {
						$arrObjects->Save();
					}
					QApplication::$Database[1]->TransactionCommit();
				} catch (QCallerException $ex) {
					QApplication::$Database[1]->TransactionRollback();
					$ex->IncrementOffset();
					throw $ex;
				} catch(Exception $ex) {
					QApplication::$Database[1]->TransactionRollback();
					throw $ex;
				}
			}

			/**
			 * Reload this page. 
			 */
			public static function Reload() {
				self::Redirect(self::$RequestUri);
			}

			/**
			 * Прокидывает параметры в коллбэк Ajax вызова
			 * 
			 * @param array $arrParametrs - массив параметров для коллбэка
			 * 
			 * @return void
			 */
			public static function SetPACallbackInfo( $arrParametrs ) {
				$strJs =  'qcubed.objPostAjaxCallbackInfo.blnIsError = false;';

				$strJs .= 'qcubed.objPostAjaxCallbackInfo.strNonFatalErrorMsg = "";';
				$strJs .= 'qcubed.objPostAjaxCallbackInfo.strPhpFile = "";';
				$strJs .= 'qcubed.objPostAjaxCallbackInfo.strPhpStringNumber = "";';

				$strJs .= 'qcubed.objPostAjaxCallbackInfo.arraySuccessCallbackParametrs = {';
				$blnFirst = true;
				foreach( $arrParametrs as $key =>$val ) {
					if ( !$blnFirst ) {
						$strJs .= ', ';
					}

					if ( is_string($val) ) {
						$val = '"' . $val . '"';
					}
					$strJs .= $key . ' : ' . $val;

					$blnFirst = false;
				}
				$strJs .= '};';

				QApplication::ExecuteJavaScript( $strJs );
			}

			/**
			 * Прокидывает в коллбэк Ajax вызова сообщение и сведенья об ошибке
			 * 
			 * @param string $strErrorMsg сообщение об ошибке
			 * 
			 * @return void
			 */
			public static function SetPACallbackError( $strErrorMsg ) {
				$arrBacktrace = debug_backtrace();

				$strJs =  'qcubed.objPostAjaxCallbackInfo.blnIsError = true;';

				$strJs .= 'qcubed.objPostAjaxCallbackInfo.strNonFatalErrorMsg = "' . $strErrorMsg . '";';
				$strJs .= 'qcubed.objPostAjaxCallbackInfo.strPhpFile = "' . $arrBacktrace[0]['file'] . '";';
				$strJs .= 'qcubed.objPostAjaxCallbackInfo.strPhpStringNumber = "' . $arrBacktrace[0]['line'] . '";';

				$strJs .= 'qcubed.objPostAjaxCallbackInfo.arraySuccessCallbackParametrs = {};';

				QApplication::ExecuteJavaScript( $strJs );
			}
			
			/**
			 * 
			 * 
			 * @param string $name  комментарий к данным для отображения
			 * @param string $value данные для отображения
			 * @param string $intLevel - уровень выравнивания по левому краю для отображения
			 * 
			 * @return void
			 */
			public static function JsConsoleLog( $name = '', $value = '', $intLevel = 0 ) {
			    if ( defined('__USE_MIHAS_JS_LOGGER__') && (true == __USE_MIHAS_JS_LOGGER__) ) {
				if ( !in_array(gettype($value), array("boolean", "integer", "double", "string") ) ) {
				    $value = json_encode($value);
				}

				$strPrefix = "";
				$intLevel = intval($intLevel);
				if ( $intLevel > 0 ) {
				    for( $i = 0; $i < $intLevel; $i++ ) {
					$strPrefix .= "   ";
				    }
				}

				$strJs = "console.log('" . $strPrefix . $name . " : " . $value . "');";
				QApplication::ExecuteJavaScript( $strJs );
			    }
			}
		}

		/**
		 * Укороченный вариант для QApplication
		 */
		abstract class QApp extends QApplication {
			/**
			 * Укороченный вариант для QApplication::Translate
			 *
			 * @param string $strToken - Английский вариант строки
			 * @return string Локализованный перевод строки
			*/
			public static function Tr($strToken) {
				return parent::Translate($strToken);
			}
			
			public static function Alert($strMessage) {
				parent::DisplayAlert(self::Tr($strMessage));
			}
		}

		// Register the autoloader
		spl_autoload_register(array('QApplication', 'Autoload'));

		//////////////////////////
		// Custom Global Functions
		//////////////////////////	
		// TODO: Define any custom global functions (if any) here...


		////////////////
		// Include Files
		////////////////
		// TODO: Include any other include files (if any) here...


		///////////////////////
		// Setup Error Handling
		///////////////////////
		/*
		 * Set Error/Exception Handling to the default
		 * QCubed HandleError and HandlException functions
		 * (Only in non CLI mode)
		 *
		 * Feel free to change, if needed, to your own
		 * custom error handling script(s).
		 */
		if (array_key_exists('SERVER_PROTOCOL', $_SERVER)) {
			set_error_handler('QcodoHandleError', error_reporting());
			set_exception_handler('QcodoHandleException');
		}


		////////////////////////////////////////////////
		// Initialize the Application and DB Connections
		////////////////////////////////////////////////
		QApplication::Initialize();
		QApplication::InitializeDatabaseConnections();
		// Check if we are going to override PHP's default session handler
		QApplication::SessionOverride();


		/////////////////////////////
		// Start Session Handler (if required)
		/////////////////////////////
		session_start();
		
		/**
		 * Initialize in-session cache object AFTER session_start call!
		 */
		QApplicationBase::$objCacheProviderSession = new QCacheProviderLocalMemory(array('KeepInSession' => true));

		$arraytmp = null;
		// Основные настройки Электронной очереди
		if (!file_exists(__EQUEUE_PHP_CONFIGURATION__ . '/iris_config.inc.php')) {
			require_once(__CONFIGURATION__ . '/conf.base.php');
			try {
				$arraytmp = ConfigBase::readConstant();
				$arrayConst = $arraytmp[1];
				$arrayConstconf = $arraytmp[0];
				ConfigBase::writeConstantphp($arrayConstconf, $arrayConst);
			} catch (Exception $ex) {
				//error_log($ex->getMessage()); //По неизвестной причине здесь не срабатывает
				iris_log(__FILE__, __LINE__, $ex->getMessage());
				throw $ex;
			}
			ConfigBase::greatFirstZip();
		}
		require_once(__EQUEUE_PHP_CONFIGURATION__ . '/iris_config.inc.php');
		

		if (!file_exists(__DOCROOT__ . __EQUEUE_JS_CONFIGURATION__ . '/constants.js')) {
			require_once(__CONFIGURATION__ . '/conf.base.php');
			try {
				if (!$arraytmp) {
					$arraytmp = ConfigBase::readConstant();
				}
				$arrayConst = $arraytmp[1];
				$arrayConstconf = $arraytmp[0];
				ConfigBase::writeConstantjs($arrayConst);
			} catch (Exception $ex) {
				//error_log($ex->getMessage()); //По неизвестной причине здесь не срабатывает
				iris_log(__FILE__, __LINE__, $ex->getMessage());
				throw $ex;
			}
		}

		if ( defined('__EQUEUE_IP_LOGGING_FEATURE_ENABLED__') && ( true == __EQUEUE_IP_LOGGING_FEATURE_ENABLED__ ) ) {
			require_once(dirname(__FILE__) . '/../../assets/php/SessionLogger.php');
			SessionLogger::SaveData();
		}

		//////////////////////////////////////////////
		// Setup Internationalization and Localization (if applicable)
		// Note, this is where you would implement code to do Language Setting discovery, as well, for example:
		// * Checking against $_GET['language_code']
		// * checking against session (example provided below)
		// * Checking the URL
		// * etc.
		// TODO: options to do this are left to the developer
		//////////////////////////////////////////////
		if (isset($_SESSION)) {
			if (array_key_exists('country_code', $_SESSION))
				QApplication::$CountryCode = $_SESSION['country_code'];
			if (array_key_exists('language_code', $_SESSION))
				QApplication::$LanguageCode = $_SESSION['language_code'];
		}

		// Initialize I18n if QApplication::$LanguageCode is set
		if (QApplication::$LanguageCode)
			QI18n::Initialize();
		else {
			// QApplication::$CountryCode = 'us';
			// QApplication::$LanguageCode = 'en';
			// QI18n::Initialize();
		}
	}
?>
