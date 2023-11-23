
//For pages and stuff
show = (args) => {
    if (args === 1){
        document.getElementById('burl').hidden = false;
    }
    else {
        document.getElementById('burl2').hidden = false;
    }

};


var D = document,
    generateURL = function() {
        var a = D.getElementById('url').value;
        var b = D.getElementById('aw').checked ? D.getElementById('aw').value : D.getElementById('kw').checked ? D.getElementById('kw').value : '';
        var c = D.getElementById('adname').value;
        if (a == '' || b == '' || c == '') {
            // $('#modal-danger').modal();
            D.getElementById('alert-secondary').hidden = true;
            D.getElementById('alert-danger').hidden = false;

        } else {
            D.getElementById('final-url').value = (a + uRL + b + c).replace(/\s/g, '');
            D.getElementById('final-url-container').hidden = false;
            D.getElementById('alert-secondary').hidden = true;
            D.getElementById('alert-danger').hidden = true;
            // $('#modal-success').modal();
        }
    },
    copyToClipboard = function() {
        var a = D.getElementById('final-url');
        a.select();
        D.execCommand("Copy");
    };

onFile = (event) => {
    var file = event.target.files[0];
    var reader = new FileReader();
    reader.onload = function(event) {
        var store = reader.result;
        parseData(store, 1);
    }
    reader.readAsText(file);
}

onFile2 = (event) => {
    var file = event.target.files[0];
    var reader = new FileReader();
    reader.onload = function(event) {
        var store = reader.result;
        parseData(store, 2);
    }
    reader.readAsText(file);
}


parseData = (data, ar) => {
    if (ar == 1){
        var allRows = data.split(/\r?\n|\r/);
        if (!Array.isArray(allRows) || allRows.length == 1){
            D.getElementById('alert-secondary1').hidden = true;
            D.getElementById('alert-danger2').hidden = false;
            return;
        }
        var test = [];
        var b = document.getElementById('baw').checked ? D.getElementById('baw').value : D.getElementById('bkw').checked ? D.getElementById('bkw').value : '';
        var c = document.getElementById('badname').value;
        if (b == '' || c == '') {
            D.getElementById('alert-secondary1').hidden = true;
            D.getElementById('alert-danger1').hidden = false;
            // $('#csv-danger').text('Please fill out all fields and re-upload the file');
            // $('#modal-danger').modal();
            return;
        }
        for (var sRow = 0; sRow < allRows.length-1; sRow++) {
            var rCells = allRows[sRow].split(',');
            for (var rcell = 0; rcell < rCells.length; rcell++) {
                var col = (rCells[rcell] + uRL + b + c).replace(/\s/g, '');
                test.push(col);
            }
        }
        Csv(test, 1);
    }
    else if (ar == 2){
        var allRows = data.split(/\r?\n|\r/);
        if (!Array.isArray(allRows) || allRows.length == 1){
            D.getElementById('alert-secondary2').hidden = true;
            D.getElementById('alert-danger4').hidden = false;
            return;
        }
        var test = [];
        var b = document.getElementById('baw2').checked ? D.getElementById('baw2').value : D.getElementById('bkw2').checked ? D.getElementById('bkw2').value : '';
        var c = document.getElementById('badname2').value;
        if (b == '' || c == '') {
            D.getElementById('alert-secondary2').hidden = true;
            D.getElementById('alert-danger3').hidden = false;
            return;
        }
        for (var sRow = 0; sRow < allRows.length-1; sRow++) {
            var rCells = allRows[sRow].split(',');
            for (var rcell = 0; rcell < rCells.length; rcell++) {
                var col = (c + uRL + b + rCells[rcell]).replace(/\s/g, '');
                test.push(col);
            }
        }
        Csv(test, 2);
    }
}

toCsv = (args, ar) => {
    var result, ctr, keys, columnDelimiter, lineDelimiter, data;
    data = args || null;
    if (ar == 1){
        if (data == null || !data.length) {
            D.getElementById('alert-secondary1').hidden = true;
            D.getElementById('alert-danger2').hidden = false;
        }
    }
    else if (ar == 2) {
        if (data == null || !data.length) {
            D.getElementById('alert-secondary2').hidden = true;
            D.getElementById('alert-danger4').hidden = false;
            // $('#csv-danger').text('Csv File Error');
            // $('#modal-danger').modal();
        }
    }
    columnDelimiter = ',';
    lineDelimiter = '\n';
    keys = data[0];
    result = '';
    result += keys + columnDelimiter;
    result += lineDelimiter;
    for (i = 1; i <= data.length - 1; i++) {
        keys = data[i];
        result += keys + columnDelimiter;
        result += lineDelimiter;
    }
    return result;
}

Csv = (args, ar) => {
    if (ar == 1){
        var data, filename, link;
        var csv = toCsv(args, ar);
        if (csv == null) {
            D.getElementById('alert-secondary1').hidden = true;
            D.getElementById('alert-danger2').hidden = false;
            // $('#csv-danger').text('Csv File Error');
            // $('#modal-danger').modal();
        };
        filename = 'output.csv';
        if (!csv.match(/^data:text\/csv/i)) {
            csv = 'data:text/csv;charset=utf-8,\n' + csv;
        }
        data = encodeURI(csv);
        var a = document.getElementById('out');
        a.setAttribute("href", data);
        a.setAttribute("download", filename);
        D.getElementById('alert-secondary1').hidden = true;
        D.getElementById('alert-danger1').hidden = true;
        D.getElementById('alert-danger2').hidden = true;
        D.getElementById('file-container1').hidden = false;
        // $('#modal-success1').modal();
    }
    else if (ar == 2) {
        var data, filename, link;
        var csv = toCsv(args, ar);
        if (csv == null) {
            D.getElementById('alert-secondary2').hidden = true;
            D.getElementById('alert-danger4').hidden = false;
            // $('#csv-danger').text('Csv File Error');
            // $('#modal-danger').modal();
        };
        filename = 'output.csv';
        if (!csv.match(/^data:text\/csv/i)) {
            csv = 'data:text/csv;charset=utf-8,\n' + csv;
        }
        data = encodeURI(csv);
        var a = document.getElementById('out2');
        a.setAttribute("href", data);
        a.setAttribute("download", filename);
        D.getElementById('alert-secondary2').hidden = true;
        D.getElementById('alert-danger3').hidden = true;
        D.getElementById('alert-danger4').hidden = true;
        D.getElementById('file-container2').hidden = false;
        // $('#modal-success2').modal();

    }

}
