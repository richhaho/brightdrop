function Node(data) {
    this.data = data;
    this.bounce = 5;
    this.parent = null;
    this.children = [];
}

function Tree(data, channel) {
    var node = new Node(data);
    this._root = node;
    this._channel = channel;
}

Tree.prototype.getChannel = function(){
    return this._channel;
}

Tree.prototype.traverseDF = function(callback) {
    // this is a recurse and immediately-invoking function
    (function recurse(currentNode) {
        for (var i = 0, length = currentNode.children.length; i < length; i++) {

            recurse(currentNode.children[i]);
        }
        callback(currentNode);

    })(this._root);
};

Tree.prototype.contains = function(callback, traversal) {
    traversal.call(this, callback);
};

Tree.prototype.add = function(data, toData, traversal) {
    var child = new Node(data), parentcheck = '',
        parent = null,
        callback = function(node) {
            if (node.data === toData) {
                // check if child already exist
                found = false;
                for (var i = 0, length = node.children.length; i < length; i++) {
                    if(node.children[i].data === data){
                        found = true;
                        parentcheck = node.data;
                        parent = 'duplicate';
                        break;
                    }
                }
                if(!found){
                    parent = node;
                }
            }
        };

    this.contains(callback, traversal);

    if (parent === 'duplicate'){
        console.log('Cannot add existent child ' + data + ' to parent ' + parentcheck);
    } else if (parent) {
        parent.children.push(child);
        child.parent = parent;
    } else {
        throw new Error('Cannot add node to a non-existent parent.' +  data + '  '  + toData);
    }
};

Tree.prototype.addArray = function(arraydata, traversal) {
    for(a = arraydata.length - 1; a >= 0; a--){
        if ((a - arraydata.length) === -1) {
            continue;
        } else {
         //   console.log(arraydata[a] + '  ' + arraydata[a + 1]);
            if(arraydata[a] === arraydata[a + 1]){
                continue;
            }
            this.add(arraydata[a], arraydata[a + 1], traversal);
        }
    }
};

var Atree = '';
Tree.prototype.parseTree = function(){
    (function recurse(currentNode, count) {
        //console.log(currentNode.data);
        Atree += '{"name": "' +  currentNode.data + '", "bounce_rate" : "' + currentNode.bounce + '"';

        if(currentNode.children.length > 0){
            Atree += ', "children": [' ;
        } else {
            Atree += ', "size": 500}';
            if (count > 0) {Atree += ', ';}
        }
        // step 2
        for (var i = 0, length = currentNode.children.length; i < length; i++) {
            // step 3
            recurse(currentNode.children[i], length - i - 1);
        }
        if(currentNode.children.length > 0){
            Atree += ']}' ;
            if (count > 0) {Atree += ', ';}
        }

        // step 1
    })(this._root);
}
var treejson = '';

var gridData = [];
var gridChannel = [];
//convData = convPos  =
function calendarWeekHour(a)
{
    conv_url = a.shift();
    channel = a.shift();

    while(a[a.length - 1] === null){
        a.pop();
    }

    var gridTree = '';
    var b = gridData.indexOf(channel);
    //console.log(conv_url + '  ____ ' + channel);

    if (b === -1){
        gridData.push(channel);
        b = gridData.indexOf(channel);
        gridtree = new Tree(conv_url, channel);
    } else {
        gridTree = gridChannel[b];
        console.log('dd');
    }

    // if (gridTree.getChannel() != channel){
    //     console.log('Incorrect channel select   ' + gridTree.getChannel() + '    ' + channel);
    //     return;
    // }
    // console.log('Incorrect channel select   ' + gridTree.getChannel()  + '    ' + channel);
    // add the paths as an array
//    urls = ['one', 'b', 'c'];   // format of paths, one is the first page visited
//    bounce = [1, 2, 3];
    console.log(a)
    if (a.length === 1) {
        return;
    }
    gridtree.addArray(a, gridtree.traverseDF);

    gridChannel[b] = gridTree;
////    gridtree.parseTree();
//
//    var json = JSON.parse(Atree);
//
//    return json;
//    tree.traverseDF(function(node) {
//    console.log(node.data)
//});
}
