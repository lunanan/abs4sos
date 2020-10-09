function verify(){

	var t = document.getElementsByName('archi_choices')[0].value; //Recupere la valeur du select
	var vulnPart = document.getElementsByName('vulne_choices');

	if(t !== 'Select a VDA' && t !== 'Select a VA'){
		vulnPart[0].style.display = "block";
		document.getElementsByName('current_va')[0].value = t;
	}else{
		vulnPart[0].style.display = "none";
	}

}

function VAtactics(){
	var select = document.getElementsByName('vuln_choices');
	var contentVA = document.getElementsByName('archi_choices')[0].value;
	
	var line = "try{$connect = SqliteConnection::getInstance()->getConnection();$dbc = SqliteConnection::getInstance()->getConnection();$query = 'select name from \'public.vulnerability_type\';$stmt = $dbc->prepare($query);$stmt->execute();$vulns = $stmt->fetchAll(PDO::FETCH_ASSOC);}catch(PDOException $e){print_r($e->getMessage());}"
	for (var i = 0; i<=nb_elem; i++){
		var opt = document.createElement('option');
		opt.value = i;
		opt.innerHTML = i;
		select.appendChild(opt);
	}
}
