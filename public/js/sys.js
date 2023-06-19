function read_error(xhr) {
	alert("Proses Gagal");

	var response = JSON.parse(xhr.responseText);
	alert("message : " + response.message);
	
	// var response = JSON.parse(xhr.responseText);
	// $.each(response, function(x, y) {
	// 	alert(x + ' : ' + y);
	// });

	return;
}

function date_db(param){
	if(param != null && param != "" && param != undefined){
		var year 	= param.substr(6, 4);
		var month 	= param.substr(3, 2);
		var day 	= param.substr(0, 2);

		var tgl = year+'-'+month+'-'+day;
		return (tgl);
	}else{
		return '';
	}
}

function date_id(param) {
	if(param != null && param != "" && param != undefined){
		var year 	= param.substr(0, 4);
		var month 	= param.substr(5, 2);
		var day 	= param.substr(8, 2);

		var tgl = day+'/'+month+'/'+year;
		return (tgl);
	}else{
		return '';
	}
}

function date_valid(param) {
	var day 	= param.substr(0, 2);
	var month 	= param.substr(3, 2);
	var year 	= param.substr(6, 4);

	if(day.length != 2 || month.length != 2 || year.length != 4){
		alert('Format tanggal harus DD/MM/YYYY ex: 31/12/2001');
	}
}

function day_id(param) {
	var day = '';

	if(param == "SUNDAY"){
		day = "MINGGU";
	}else if (param == "MONDAY"){
		day = "SENIN";
	}else if (param == "TUESDAY"){
		day = "SELASA";
	}else if (param == "WEDNESDAY"){
		day = "RABU";
	}else if (param == "THURSDAY"){
		day = "KAMIS";
	}else if (param == "FRIDAY"){
		day = "JUMAT";
	}else if (param == "SATURDAY") {
		day = "SABTU";
	}else {
		day = '';
	}

	return (day);
}

var list_day = [
	{"id":"MONDAY","text":"SENIN"},
	{"id":"TUESDAY","text":"SELASA"},
	{"id":"WEDNESDAY","text":"RABU"},
	{"id":"THURSDAY","text":"KAMIS"},
	{"id":"FRIDAY","text":"JUMAT"},
	{"id":"SATURDAY","text":"SABTU"},
	{"id":"SUNDAY","text":"MINGGU"}
];

function time_id (param) {
	if(param != null && param != "" && param != undefined){
		var hour 	= param.substr(0, 2);
		var minute	= param.substr(3, 2);

		var time = hour+':'+minute;
		return (time);
	}else{
		return '';
	}
}

function number_format_id(param) {
	var nilai = new Intl.NumberFormat('id-ID').format(param);

	return nilai;
}

function formatNumber(num,prefix){
	prefix = prefix || '';
	num += '';
	var splitStr = num.split('.');
	var splitLeft = splitStr[0];
	var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '';
	var regx = /(\d+)(\d{3})/;
	while (regx.test(splitLeft)) {
		splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
	}
	return prefix + splitLeft + splitRight;
}

function unformatNumber(num) {
	if (typeof num != "undefined") 
        return num.replace(/([^0-9\.\-])/g,'')*1;
}

// function unformatNumber(num) {
// 	if(num != null && num != "" && num != undefined){
// 		return num.replace(/([^0-9\.\-])/g,'')*1;
// 	}else{
// 		return '';
// 	}
// }

function AddZero(num) {
	return (num >= 0 && num < 10) ? "0" + num : num + "";
}

var now   = new Date();
var month = new Array();
	month[0]  = "Jan";
	month[1]  = "Feb";
	month[2]  = "Mar";
	month[3]  = "Apr";
	month[4]  = "May";
	month[5]  = "Jun";
	month[6]  = "Jul";
	month[7]  = "Aug";
	month[8]  = "Sep";
	month[9]  = "Oct"; 
	month[10] = "Nov";
	month[11] = "Dec";
var bulan 		= month[now.getMonth()];
var strDateTime = [[AddZero(now.getDate()), bulan, now.getFullYear()].join(" "), [AddZero(now.getHours()), AddZero(now.getMinutes())].join(":"), now.getHours() >= 12 ? "PM" : "AM"].join(" ");

function cekJenisPecahan(main, another) {
	var mainRows    = main.children(),
		anotherRows = another.children();

		mainRows.each(function(rowNumber, mainRow) {
			var anotherRow      = anotherRows.eq(rowNumber),
				anotherCells    = anotherRow.children(),
				mainCells       = $(mainRow).children();
				
				mainCells.each(function(colNumber, cell) {
					var anotherCell = anotherCells.eq(colNumber)

					if(anotherCell.text() !== $(cell).text())
						alert('Jenis Pecahan Voucher Pengeluaran, Tidak Sesuai dengan Jenis Pecahan Voucher Pengajuan');
						// anotherCell.css('background-color', 'yellow').css('color', 'red');
				})
		});
}

function validasi_email(mail){
 	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
    	return (true)
  	}

    return (false)
}

function format_time($date){
	var d = new Date($date);
	var bln = ["January","February","March","April","May","June","July","August","September","October","November","December"];
	var date = d.getDate();
	var month = bln[d.getMonth()];
	var year = d.getFullYear();
	if ($date == null) {
		return "-";
	} else {
		return date+" "+month+" "+year;
	}
}

function nl(data){
	if (data == null) {
		return "";
	} else {
		return data;
	}
};