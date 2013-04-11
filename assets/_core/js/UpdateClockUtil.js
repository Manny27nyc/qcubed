function QServerTime() {
	this.currentServerTime = null;
	this.lastServerTimeUpdate = null;
	this.lastServerTimestamp = null;
	/**
	 * @property {boolean} Фиксировать возвращаемое функциями время на начало текущих суток.
	 * Используется функционалом, работающим с ПЛАНовым распределением талонов
	 * (в отличие от ФАКТического).
	 */
	this.lockDayStart = false;
}
with(QServerTime) {
	
	/**
	 * Получить текущее время сервера, либо начало текущих серверных суток,
	 * если выставлен флаг lockDayStart, в миллисекундах.
	 * @return {int} Текущее время сервера, либо начало текущих серверных суток,
	 * если выставлен флаг lockDayStart, в миллисекундах.
	 */
	prototype.getTime = function() {
		var timeNow = new Date;
		if (!this.currentServerTime || !this.lastServerTimeUpdate) {
			return timeNow;
		}
		
		// Чтобы ФАКТическое состояние очереди не влияло на ПЛАНовое размещение талонов в очереди,
		// текущее время выставляется в самое начало суток.
		var tm = this.currentServerTime.getTime() + (timeNow.getTime() - this.lastServerTimeUpdate.getTime());
		if (this.lockDayStart) {
			var timeDayStart = new Date(tm);
			timeDayStart.setHours(0, 0, 0, 0);
			return timeDayStart.getTime();
		}
		return tm;
	}

	/**
	 * Получить текущее время сервера, либо начало текущих серверных суток,
	 * если выставлен флаг lockDayStart.
	 * @return {Date} Текущее время сервера, либо начало текущих серверных суток,
	 * если выставлен флаг lockDayStart.
	 */
	prototype.getObjDate = function() {
		return new Date( this.getTime() );
	}

	/**
	 * @param {Date} objDate
	 * @return string Дата и время в формате ГГГГ-ММ-ДД ЧЧ:ММ:СС
	 */
	prototype.formatDateTime = function( objDate ) {
		return this.formatDate(objDate) + " " + this.formatTime(objDate);
	}

	/**
	 * @param {Date} objDate
	 * @return string Дата в формате ГГГГ-ММ-ДД
	 */
	prototype.formatDate = function( objDate ) {
		if ( !objDate.getFullYear || ! objDate.getMonth || !objDate.getDate ) {
			return null;
		}

		// дату в формат "Y/n/j" DateTime'а
		return "" + objDate.getFullYear() + '/' + (objDate.getMonth()+1) + '/' + objDate.getDate();
	}

	/**
	 * @param {Date} objDate
	 * @return string Дата в формате ГГГГ-ММ-ДД
	 */
	prototype.formatData = function( objDate ) {
		if ( !objDate.getFullYear || ! objDate.getMonth || !objDate.getDate ) {
			return null;
		}

		// дату в формат "Y/n/j" DateTime'а
		return "" + objDate.getFullYear() + '/' + (objDate.getMonth()+1) + '/' + objDate.getDate();
	}

	/**
	 * @param {Date} objDate
	 * @return string время в формате ЧЧ:ММ:СС
	 */
	prototype.formatTime = function( objDate ) {
		if ( !objDate.getHours || ! objDate.getMinutes || !objDate.getSeconds ) {
			return null;
		}

		// время в формат "H:i:s" DateTime'а
		var strTime = "";

		if ( objDate.getHours() < 10 ) {
			strTime += '0';
		}
		strTime += objDate.getHours() + ':';

		if ( objDate.getMinutes() < 10 ) {
			strTime += '0';
		}
		strTime += objDate.getMinutes() + ':';

		if ( objDate.getSeconds() < 10 ) {
			strTime += '0';
		}
		strTime += objDate.getSeconds();               

		return strTime;
	}

	/**
	*/
	prototype.getServerTimestamp = function() {
		var timeNow = new Date;
		if (!this.currentServerTime || !this.lastServerTimeUpdate) {
			return null;
		}
		return this.lastServerTimestamp + (timeNow.getTime() - this.lastServerTimeUpdate.getTime());
	}

	/**
	* Функция, принимающая текущее значение серверного времени и синхронизирующая с ней
	* дату на стороне клиента. ВАЖНО : часовой пояс на дате клиента остаётся локальным,
	* но часы, минуты, секунды и вся остальная информация соответствует серверной
	*/
	prototype.setServerTime = function( intServerTime, intServerTimezoneOffset ) {
		var currentServerTime = new Date( intServerTime );


		var localTimezoneOffset = currentServerTime.getTimezoneOffset()*60*1000; // минуты => миллисекунды

		/*
		console.log( '--------------------------------------' );
		console.log( 'TimezoneOffset (local)     : ' + localTimezoneOffset);
		console.log( 'TimezoneOffset (from serv) : ' + intServerTimezoneOffset );
		console.log( '----------------------------' );
		*/

		var delta = localTimezoneOffset - intServerTimezoneOffset;
		currentServerTime.setTime( currentServerTime.getTime() + delta );

		/*
		var d = new Date( intServerTime );
		console.log( 'Date (local)     : ' + d );
		d.setTime( d.getTime() + delta );
		console.log( 'Date (formatted) : ' + d );
		*/

		this.lastServerTimeUpdate = new Date();
		this.currentServerTime = currentServerTime;
		this.lastServerTimestamp = intServerTime;
	}
}

var objServerTime = new QServerTime();
