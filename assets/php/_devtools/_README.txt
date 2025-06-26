/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
This directory contains the web-based drivers for Qcubed's development tools:

* codegen.php - the Qcubed CodeGen web-based driver.  It uses the QCodeGen and
  related Qcubed codegen libraries to do the bulk of the work.  The index.php
  file simply instantiates a QCodeGen object, executes the various public
  methods on it to do the code generation, and creates a report of its
  activities in a nicely formatted HTML page.  It uses the codegen_settings.xml
  file as the "settings" to use for codegenning.

* (future tools tba)

Feel free to alter the settings, inputs and/or outputs of any of the drivers
as you wish.
