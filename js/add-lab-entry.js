/**
 * @author Prasad Rajandran
 * @date July 1, 2013 
 */

var labEntryCount = 0; //stores the current row number
var idTableRow    = null; //table row id used to identify each row individually

//calls the function when the document has loaded
$(function(){
   addLabEntry(); 
});

//appends new a row when the input field immediately preceding it is filled
function addLabEntry() {
    labEntryCount++; //row index number
    updateIDRow(); //updates the table row id (used by jQuery as a selector)
    
    var idTableCol1 = "#labTableCol1ID" + labEntryCount; //updates column 1 IDs (used by jQuery as a selector)
    var idTableCol2 = "#labTableCol2ID" + labEntryCount; //updates column 2 IDs (used by jQuery as a selector)
    
    //appends input fields for a new row to the end of the table
    $("tbody").append('<tr id="labTableRowID' + labEntryCount + '"></tr>');
    
    //column 1
    $(idTableRow).append('<td id="labTableCol1ID' + labEntryCount + '"></td>'); //labTableCol1ID1, labTableCol1ID2, ... <- the labEntryCount variable is used to distinguish between IDs
    //lab input field
    $(idTableCol1).append('<span class="nowrap">' + labEntryCount + '. ' + '<input type="text" id="lab' + labEntryCount + '" name="lab' + labEntryCount + '" data-index="' + labEntryCount + '" maxlength="100" size="35" /></span><br />'); //lab1, lab2, ...
    //checkboxes
    $(idTableCol1).append('<br /><span class="checkboxes">Charges a fee for use: <select id="fee' + labEntryCount + '" name="fee' + labEntryCount + '"><option value="1">Yes</option><option value="0">No</option><option value="2">Varies</option></select></span>'); //fee1, fee2, ...
    $(idTableCol1).append('<br /><span class="checkboxes">Access by guest researcher allowed: <select id="guest' + labEntryCount + '" name="guest' + labEntryCount + '"><option value="1">Yes</option><option value="0">No</option></select></span>'); //guest1, guest2, ...
    $(idTableCol1).append('<br /><span class="checkboxes">Host technician(s) available: <select id="host' + labEntryCount + '" name="host' + labEntryCount + '"><option value="1">Yes</option><option value="0">No</option></select></span>'); //host1, host2, ...

    //column 2
    $(idTableRow).append('<td id="labTableCol2ID' + labEntryCount + '"></td>'); 
    //description textarea 
    $(idTableCol2).append('<textarea id="descr' + labEntryCount + '" name="descr' + labEntryCount + '" rows="5" maxlength="750"></textarea>'); //descr1, descr2, ...
    $(idTableCol2).append('<br /><span class="hint">(Maximum number of characters: 750)</span><br /><br />');

    //attaches the event handler to check for input
    addEventHandler();
}

/**
 * attaches an event handler to each table row for the specialized lab list portion
 * of the table. 
 */
function addEventHandler() {
    var id = idTableRow + " input[type='text']"; //gets the lab name field (jQuery selector)
    var index = $(id).attr("data-index"); //gets the row index number
    
    //these are javascript events. used multiple events in case one failed to detect input
    $(id).keyup(function() {addNewLabEntry(this);}); 
    $(id).change(function() {addNewLabEntry(this);}); 
    $(id).click(function() {addNewLabEntry(this);});
    $(id).mouseout(function() {addNewLabEntry(this);});
    
    /* this function decides if a row should be added or removed
     * if the field above it is empty, it will remove itself (except for the first row).
     * if the field is filled, it will add a row below itself.
     */
    function addNewLabEntry(tag) {
        if($(tag).val() != "") {
            if(labEntryCount == index && labEntryCount < MAX_LAB_ENTRIES) { 
                addLabEntry();
            }
        }      
    }
}

/**
 * updates the table row id 
 */
function updateIDRow() {
    idTableRow = "#labTableRowID" + labEntryCount; //labTableRowID1, labTableRowID2, ...
}