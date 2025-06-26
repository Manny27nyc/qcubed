/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php
/**
 * Control categories, used by the ModelConnectorEditDlg to pair controls with database types or relationships *
 */

abstract class QControlCategoryType {
	/** Large binary object or large text data */
	const Blob = QDatabaseFieldType::Blob;
	/** Character sequence - variable length */
	const Text = QDatabaseFieldType::VarChar;
	/** Character sequence - fixed length */
	const Char = QDatabaseFieldType::Char;
	/** Integers */
	const Integer = QDatabaseFieldType::Integer;
	/** Date and Time together */
	const DateTime = QDatabaseFieldType::DateTime;
	/** Date only */
	const Date = QDatabaseFieldType::Date;
	/** Time only */
	const Time = QDatabaseFieldType::Time;
	/** Float, Double and real (postgresql) */
	const Float = QDatabaseFieldType::Float;
	/** Boolean */
	const Boolean = QDatabaseFieldType::Bit;
	/** Select one item from a list of items. A foreign key or a unique reverse relationship. */
	const SingleSelect = 'single';
	/** Select multiple items from a list of items. A non-unique reverse relationship or association table. */
	const MultiSelect = 'multi';
	/** Display a representation of an entire database table. Click actions would typically be done on this list. */
	const Table = 'table';
}
