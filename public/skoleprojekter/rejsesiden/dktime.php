<script type="text/javascript">
// display current time at author location
// =======================================
// copyright Stephen Chapman, Felgall Pty Ltd, 11 July 2001, 25th November 2004
// http://www.felgall.com/ and http://javascript.about.com/
// permission is given to use this script
// provided that all comment lines in the script are retained

function dkTime() {
	var dkdst = 0;       // set to 1 for daylight savings time
	                   // update this as you go on and off daylight saving time
	
	var loc = 'Danmark'; // set to your location
	var dkmtz = 1;      // set to your local timezone (hours ahead of UTC, negative if behind)
	var dkstdz = ''; // standard time indicator
	var dkdayz = 'Adkdst'; // daylight saving time indicator (blank if you dont have daylight saving)
	
	// do not alter anything below this line
	document.writeln('Klokken i ' + loc + ': <span id="dktime">' + setDsp(dkmtz,dkdst,dkstdz,dkdayz) + '<\/span>');
	if (DOM_supported) setTimeout('upd_Dsp('+dkmtz+',' +dkdst+',"' +dkstdz+'","' +dkdayz+'")', 5000);
}

var DOM_supported = 0;
var standard_DOMsupported = 0;
var ie_DOMsupported = 0;
if (document.getElementById) {
	standard_DOMsupported = 1;
	DOM_supported = 1;
} else {
	if (document.all) {
		ie_DOMsupported = 1;
		DOM_supported = 1;
	}
}

function find_DOM(object_Id) {
	if (standard_DOMsupported) {
		return (document.getElementById(object_Id));
	}
	if (ie_DOMsupported) {
		return (document.all[object_Id]);
	}
}

function upd_Dsp(dkmtz,dkdst,dkstdz,dkdayz) {
	var obj = find_DOM('dktime');
	obj.innerHTML = set_Dsp(dkmtz,dkdst,dkstdz,dkdayz);
	setTimeout('upd_Dsp('+dkmtz+ ','+dkdst+ ',"'+dkstdz+ '","'+dkdayz+ '")', 5000);
}

function set_Dsp(dkmtz,dkdst,dkstdz,dkdayz) {
	var dayname = new Array ('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday','Friday', 'Saturday', 'Sunday');
	var now = new Date;
	now.setUTCMinutes(now.getUTCMinutes() + (dkmtz + dkdst)*60);
	var dow = now.getUTCDay();
	var minute = now.getUTCMinutes();	
	var hour = now.getUTCHours();
	/*if (hour > 11) {
		ampm = 'PM';
		hour -= 12;
	} else {
		ampm = 'AM';
	}*/
	/*if (hour == 0) {
		hour = 12;
	} */
	if (minute < 10) {
		pad = ':0';
	} else {
		pad = ':';
	}
	var txt = hour + pad + minute;
	if (dkdst) txt += dkdayz;
	else txt += dkstdz;
	return (txt);
}
</script>