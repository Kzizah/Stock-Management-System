
var cities = {
    'Baringo': ['Kabarnet', 'Eldama Ravine', 'Marigat', 'Mogotio', 'Tiaty'],
    'Bomet': ['Bomet', 'Sotik', 'Chepalungu', 'Bomet East', 'Konoin'],
    'Bungoma': ['Bungoma', 'Kimilili', 'Webuye', 'Chwele', 'Sirisia'],
    'Busia': ['Busia', 'Malaba', 'Nambale', 'Butula', 'Funyula'],
    'Elgeyo-Marakwet': ['Iten', 'Kapsowar', 'Chepkorio', 'Kimwarer', 'Tambach'],
    'Embu': ['Embu', 'Runyenjes', 'Mbeere', 'Kiritiri', 'Siakago'],
    'Garissa': ['Garissa', 'Dadaab', 'Masalani', 'Hulugho', 'Modogashe'],
    'Homa Bay': ['Homa Bay', 'Mbita', 'Oyugis', 'Kendu Bay', 'Ndhiwa'],
    'Isiolo': ['Isiolo', 'Garbatulla', 'Merti', 'Oldonyiro', 'Kinna'],
    'Kajiado': ['Kajiado', 'Ngong', 'Kitengela', 'Loitokitok', 'Namanga'],
    'Kakamega': ['Kakamega', 'Mumias', 'Butere', 'Shinyalu', 'Lugari'],
    'Kericho': ['Kericho', 'Litein', 'Kipkelion', 'Sigowet', 'Kimulot'],
    'Kiambu': ['Kiambu', 'Thika', 'Ruiru', 'Limuru', 'Gatundu'],
    'Kilifi': ['Kilifi', 'Malindi', 'Mtwapa', 'Garsen', 'Mariakani'],
    'Kirinyaga': ['Kerugoya', 'Kutus', 'Kagumo', 'Kianyaga', 'Kagio'],
    'Kisii': ['Kisii', 'Ogembo', 'Nyamache', 'Keroka', 'Masimba'],
    'Kisumu': ['Kisumu', 'Ahero', 'Maseno', 'Muhoroni', 'Nyando'],
    'Kitui': ['Kitui', 'Mwingi', 'Mutomo', 'Ikutha', 'Zombe'],
    'Kwale': ['Kwale', 'Diani', 'Ukunda', 'Lunga Lunga', 'Kinango'],
    'Laikipia': ['Nanyuki', 'Nyahururu', 'Rumuruti', 'Doldol', 'Kinamba'],
    'Lamu': ['Lamu', 'Mpeketoni', 'Faza', 'Witu', 'Kiunga'],
    'Machakos': ['Machakos', 'Kangundo', 'Matuu', 'Masii', 'Athi River'],
    'Makueni': ['Wote', 'Makindu', 'Mbooni', 'Mtito Andei', 'Kibwezi'],
    'Mandera': ['Mandera', 'Elwak', 'Rhamu', 'Takaba', 'Banisa'],
    'Marsabit': ['Marsabit', 'Moyale', 'Sololo', 'Laisamis', 'North Horr'],
    'Meru': ['Meru', 'Maua', 'Nkubu', 'Timau', 'Laare'],
    'Migori': ['Migori', 'Rongo', 'Awendo', 'Kuria', 'Isebania'],
    'Mombasa': ['Mombasa', 'Likoni', 'Nyali', 'Changamwe', 'Kisauni'],
    'Murang’a': ['Murang’a', 'Kangema', 'Kandara', 'Maragua', 'Sabasaba'],
    'Nairobi': ['Nairobi', 'Westlands', 'Lang’ata', 'Dagoretti', 'Embakasi'],
    'Nakuru': ['Nakuru', 'Naivasha', 'Gilgil', 'Molo', 'Njoro'],
    'Nandi': ['Kapsabet', 'Nandi Hills', 'Kabiyet', 'Chemundu', 'Kilibwoni'],
    'Narok': ['Narok', 'Kilgoris', 'Ololunga', 'Loita', 'Suswa'],
    'Nyamira': ['Nyamira', 'Keroka', 'Nyamusi', 'Manga', 'Borabu'],
    'Nyandarua': ['Ol Kalou', 'Ndaragwa', 'Engineer', 'Mairo Inya', 'Kinangop'],
    'Nyeri': ['Nyeri', 'Karatina', 'Othaya', 'Mukurwe-ini', 'Mweiga'],
    'Samburu': ['Maralal', 'Baragoi', 'Wamba', 'Archers Post', 'South Horr'],
    'Siaya': ['Siaya', 'Bondo', 'Ugunja', 'Usenge', 'Yala'],
    'Taita-Taveta': ['Voi', 'Taveta', 'Wundanyi', 'Mwatate', 'Maungu'],
    'Tana River': ['Hola', 'Garsen', 'Bura', 'Bangale', 'Madogo'],
    'Tharaka Nithi': ['Chuka', 'Marimanti', 'Chiakariga', 'Gatunga', 'Kaanwa'],
    'Trans Nzoia': ['Kitale', 'Endebess', 'Kiminini', 'Kaplamai', 'Cherangany'],
    'Turkana': ['Lodwar', 'Kakuma', 'Lokichogio', 'Lokitaung', 'Kalokol'],
    'Uasin Gishu': ['Eldoret', 'Moiben', 'Ziwa', 'Turbo', 'Burnt Forest'],
    'Vihiga': ['Mbale', 'Luanda', 'Chavakali', 'Hamisi', 'Sabatia'],
    'Wajir': ['Wajir', 'Habaswein', 'Griftu', 'Eldas', 'Bute'],
    'West Pokot': ['Kapenguria', 'Chepareria', 'Sigor', 'Alale', 'Ortum']
};


 var City = function() {

	this.p = [],this.c = [],this.a = [],this.e = {};
	window.onerror = function() { return true; }
	
	this.getProvinces = function() {
		for(let province in cities) {
			this.p.push(province);
		}
		return this.p;
	}
	this.getCities = function(province) {
		if(province.length==0) {
			console.error('Please input county name');
			return;
		}
		for(let i=0;i<=cities[province].length-1;i++) {
			this.c.push(cities[province][i]);
		}
		return this.c;
	}
	this.getAllCities = function() {
		for(let i in cities) {
			for(let j=0;j<=cities[i].length-1;j++) {
				this.a.push(cities[i][j]);
			}
		}
		this.a.sort();
		return this.a;
	}
	this.showProvinces = function(element) {
		var str = '<option selected disabled>Select County</option>';
		for(let i in this.getProvinces()) {
			str+='<option>'+this.p[i]+'</option>';
		}
		this.p = [];		
		document.querySelector(element).innerHTML = '';
		document.querySelector(element).innerHTML = str;
		this.e = element;
		return this;
	}
	this.showCities = function(province,element) {
		var str = '<option selected disabled>Select Subcounty / Municipality</option>';
		var elem = '';
		if((province.indexOf(".")!==-1 || province.indexOf("#")!==-1)) {
			elem = province;
		}
		else {
			for(let i in this.getCities(province)) {
				str+='<option>'+this.c[i]+'</option>';
			}
			elem = element;
		}
		this.c = [];
		document.querySelector(elem).innerHTML = '';
		document.querySelector(elem).innerHTML = str;
		document.querySelector(this.e).onchange = function() {		
			var Obj = new City();
			Obj.showCities(this.value,elem);
		}
		return this;
	}
}
