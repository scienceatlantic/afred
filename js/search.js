/**
 * @author Prasad Rajandran
 * @date July 1, 2013 
 */

var hideProgressBarInterval = null; //clearTimeout variable for hiding the progress bar
var showProgressBarInterval = null; //clearTimeout variable for showing the progress bar
var currentSearchQuery      = null; //stores the most recent search query
var LIST_ALL                = 0; //defined number to represent the "List all" feature
var BASIC_SEARCH            = 1; //defined number to represent the "basic search" feature
var LIST_ALL_DEACTIVATED    = 2; //defined number to represent the "List all deactivated" feature 
var SHOW_PROGRESS_BAR       = 1; //defined number to indicate when to show the progress bar
var HIDE_PROGRESS_BAR       = 0; //defined number to indicate when to hide the progress bar
var NUM_SEARCH_COLUMNS      = 6; //the number of columns the search results table contains - 1 (minus 1 because the ID column is repeated both in the ID column and in the Institution column --> href link)

$(function() {
   //list all
   $("#listAllButton").click(function() {
       $("#search").val("");
       search(LIST_ALL);
   });
   
   //list all deactivated
   $("#listAllDeactivatedButton").click(function() {
       $("#search").val("");
       search(LIST_ALL_DEACTIVATED);
   });
   
   //clear
   $("#clearButton").click(function() {
       $("#search").val("");
       search(BASIC_SEARCH);
   });
   
   //search field
   $("#search").keyup(function() {
      search(BASIC_SEARCH); 
   });
   
   //search tips
   $("#searchTips button").click(function() {
   		$("#searchTipsBullets").toggle();
   });
   addSearchTips();
});

/**
 * Searches the database based on input from the search field
 * or if the "list all" or "list all deactivated" buttons were
 * clicked
 * @param {Number} searchType - the search type, i.e. "List all", "List all deactivated", "Basic search"
 */
function search(searchType) {
    var query = getSearchQuery(); //gets the search query
    var sameQuery = isSameQuery(); //checks if this is a new search. This prevents the system from searching again if any keyboard button was pressed unless it's a new search 
    
    //checks if the search query is not empty AND it's not the same search query OR if it's a "list all" request OR if it's a "list all deactivated" request
    if((query != "" && !sameQuery) || searchType == LIST_ALL || searchType == LIST_ALL_DEACTIVATED) {
    	
    	/* the clear button is disabled when a search is performed. this is done because of callback functions.
    	 * a search might still be in progress and it may display the results even after the clear button was clicked.
    	 * so it's easier just to disable the button and only enable it when a search is not being performed.
    	 */
    	$("#clearButton").prop("disabled", true);
    	
        $("#searchResults").empty(); //removes the current search table
        progressBar(SHOW_PROGRESS_BAR); //shows the progress bar
        $("#searchTips").hide(); //hides the search tips
        
        //based on the search type, this chunk of code makes the necessary AJAX calls
        if (searchType == LIST_ALL) {
            $.get(HTTP_REQUEST, {request: "list all"}, function(data) {
                showSearchResults(query, data);               
            });
        }
        else if(searchType == BASIC_SEARCH) {
            $.get(HTTP_REQUEST, {request: "basic search", query: query}, function(data) {
                showSearchResults(query, data);
            });       
        }
        else if(searchType == LIST_ALL_DEACTIVATED) {
            $.get(HTTP_REQUEST, {request: "list all deactivated", query: query}, function(data) {
                showSearchResults(query, data);
            });            
        }
    }
    //
    //if it's not the same query, clear the search results and show the search tips
   /*else if(!sameQuery) {
        $("#searchResults").empty();
        $("#searchTips").show();
    }*/
    //if the search query is blank, clear the search results and show the search tips
    else if(query == "") {
        $("#searchResults").empty();
        $("#searchTips").show();
    }
}

/**
 * Displays the search results in a table
 * @param {String} query - search query
 * @param {String} data - search results received by the AJAX call separated by the DELIMITER
 */
function showSearchResults(query, data) {
    //if this is not the most current search query, hide the progress bar and return
    //this makes sure that the search results displayed are based on the most current search query
    if(query != currentSearchQuery) {
        progressBar(HIDE_PROGRESS_BAR);
        return;
    }
    
    var searchResults = data.split(DELIMITER); //separates the data
    var numSearchResults = 0;
    if(searchResults.length > 1) {
        numSearchResults = searchResults.length/NUM_SEARCH_COLUMNS; //calculates the number of search results
    }
    
    progressBar(HIDE_PROGRESS_BAR); //hides the progress bar
    $("#searchResults").empty(); //empties the search table to make way for the new search results
    
    //if there's only one entry in the search results, append "# search result" else append "# search results"
    if(numSearchResults == 1) {
        $("#searchResults").append("<br />" + numSearchResults + " Search result<br /><br />");                
    }
    else {
        $("#searchResults").append("<br />" + numSearchResults + " Search results<br /><br />");
    }
    
    //display the search table if the search returned more than 0 entries
    if(numSearchResults > 0) {
        $("#searchResults").append("<table id='searchTable' class='tablesorter'></table>"); //attaches the tablesorter class to the table for the ability to sort table headers
        $("#searchTable").append("<thead><tr><th>No.</th><th>ID</th><th>Research Facility</th><th>Institution</th><th>City</th><th>Province</th><th>Date Updated</th></tr></thead>");
        $("#searchTable").append("<tbody id='searchTableBody'></tbody>");
        for(var index = 0, count = 1; index < searchResults.length; index++, count++) {
            $("#searchTableBody").append("<tr class='highlight'><td>" + count + ".</td><td>" + searchResults[index] + "</td><td>" + "<a href='"+ EQUIPMENT_LISTING_PAGE + searchResults[index] + "' target='_blank'>" + searchResults[++index].trim() + "</a><br />" + 
            "</td><td>" + searchResults[++index].trim() + "</td><td>" + searchResults[++index].trim() + "</td><td>" + searchResults[++index].trim().substr(0, 2) + "</td><td>" + searchResults[++index].substring(0, 10) + "</td></tr>");        
        }
        $("#searchTable").tablesorter({sortList: [[0,0]]}); //sets the table to sort by No.      
    }
    
    //enable the clear button once a search is complete.
    $("#clearButton").prop("disabled", false);
}

/**
 * Shows or hides the progress bar
 * @param {Number} mode - show/hide progress bar
 */
function progressBar(mode) {
    if(mode == SHOW_PROGRESS_BAR) {
        clearInterval(showProgressBarInterval);
        clearInterval(hideProgressBarInterval);
        $("#progressBar").empty();        
    }
    else if(mode == HIDE_PROGRESS_BAR) {
        clearInterval(showProgressBarInterval);
        clearInterval(hideProgressBarInterval);
        $("#progressBar").empty();
        return;
    }
    
    //appends "|" every 5 milliseconds
    showProgressBarInterval = setInterval(function() {
        $("#progressBar").append("|"); 
    }, 5);        

    //clears the progress bar after 500 milliseconds
    //this prevents the progress bar from overflowing
    hideProgressBarInterval = setInterval(function(){
        $("#progressBar").empty();
    }, 500);
}

/**
 * Returns the search query 
 */
function getSearchQuery() {
    return $("#search").val().replace(/\s+/g, ' ').trim(); //trims redundant whitespace
}

/**
 * Checks if it's a new search query
 * @return true if it's the same query or false if it's not 
 */
function isSameQuery() {
    var query = getSearchQuery();
    if(query == currentSearchQuery) {    
        return true;
    }
    else {
        currentSearchQuery = query; //if it's not the same query, set the global variable to the current search query
        return false;
    }    
}

/**
 * Add search tips to the search tips container after hiding it first.
 */
function addSearchTips() {
	$("#searchTipsBullets").hide();
	$("#searchTipsBullets").append("<li>Use the “List all” button to see all facilities included in the database (note: some facilities may have more than one piece of equipment in their listing).</li>");
	$("#searchTipsBullets").append("<li>To find entries added/updated since your last visit, click \"List all\" and sort by \"Date Updated\" (far right column).</li>");
	$("#searchTipsBullets").append("<li>You can type in any Atlantic city or province (full name, example: Nova Scotia) for a list of all facilities in a specific geographic region.</li>");
	$("#searchTipsBullets").append("<li>All content in the database is indexed in this search, so typing a name, partial phone number, record number, etc., will bring up the relevant record(s).</li>");	
}