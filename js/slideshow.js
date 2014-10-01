/**
 * @author Prasad Rajandran
 * #date: August 14, 2013 
 */

var IMAGE_SRC          		= "img/slideshow/"; //the location and name of the images
var IMAGE_EXT           	= ".jpg"; //image file extension
var IMAGE_WIDTH         	= "320px"; //desired width of the image
var IMAGE_HEIGHT 			= "240px"; //desire height of the image
var NUM_OF_IMG          	= 34; //number of images available
var IMAGE_SELECTOR  		= "#homePageSlideshowImageContainer"; //name of the container holding the image
var LOAD_MSG_SELECTOR		= "#homePageSlideshowLoadMessage";
var CAPTION_SELECTOR		= "#homePageSlideshowCaption";
var SLIDESHOW_INTERVAL  	= 3500; //length of time before a new image is displayed (in milliseconds)
var FADE_IN_DELAY       	= 600; //length of time it takes to load a new image (in milliseconds) !this value and the value directly below it cannot be larger than SLIDESHOW_INTERVAL
var FADE_OUT_DELAY      	= 600; //length of time it takes to load a new image (in milliseconds) 
var firstTime				= true; //first time loading the slideshow?
var imageNum          		= 0; //the index number of the image

$(function() {
	imageBuffer();
});

/**
 * this function loads all the slideshow images first so that the slideshow runs
 * smoothly without any image loading issues.
 */
function imageBuffer() {
	$(IMAGE_SELECTOR).hide();
	for(imageNum = 1; imageNum <= NUM_OF_IMG; imageNum++) {
		appendImage();
		
		//if it's loading the final image
		if(imageNum == NUM_OF_IMG) {
			$(window).load(function() {
				$(IMAGE_SELECTOR).empty();
				slideshow();
				//calls the slideshow function
				setInterval(function() {
					slideshow();
				}, SLIDESHOW_INTERVAL);
			});	
		}
	}
}

/**
 * this function displays a slideshow
 */
function slideshow() {
	$(LOAD_MSG_SELECTOR).remove();
	
	if(++imageNum > NUM_OF_IMG) {
		imageNum = 1
	}
	
	var imageAndCaptionSelector = IMAGE_SELECTOR + "," + CAPTION_SELECTOR; //jQuery selector workaround, put both selector variables into a single string
	if(firstTime) {
		$(imageAndCaptionSelector).hide();
		appendImage();
		$(imageAndCaptionSelector).fadeIn(FADE_IN_DELAY);
		appendCaption();
		firstTime = false;	
	}
	else {
		$(imageAndCaptionSelector).fadeOut(FADE_OUT_DELAY, function() {
			$(IMAGE_SELECTOR).empty();
			appendImage();
			appendCaption();
			$(imageAndCaptionSelector).fadeIn(FADE_IN_DELAY);
		});		
	}
}

/**
 * this function appends an image to the container
 */
function appendImage() {
	if(imageNum < 10) {
		var filename = IMAGE_SRC + "0" + imageNum + IMAGE_EXT;		
	}
	else {
		var filename = IMAGE_SRC + imageNum + IMAGE_EXT;	
	}
	$(IMAGE_SELECTOR).append('<img src="' + filename + '" width="' + IMAGE_WIDTH + '" width="' + IMAGE_HEIGHT + '" alt="Equipment Slideshow" />');	
}

/**
 * this functions inserts a caption into the container
 */
function appendCaption() {
	$("#homePageSlideshowCaption").empty();
	switch(imageNum) {
		case 1: $("#homePageSlideshowCaption").append("Annular Reactors<br>&copy; Acadia University"); 
				break;
		case 2: $("#homePageSlideshowCaption").append("HG Analysis<br>&copy; Acadia University"); 
				break;
		case 3: $("#homePageSlideshowCaption").append("&copy; Dalhousie University"); 
				break;
		case 4: $("#homePageSlideshowCaption").append("&copy; Dalhousie University"); 
				break;
		case 5: $("#homePageSlideshowCaption").append("&copy; Dalhousie University"); 
				break;
		case 6: $("#homePageSlideshowCaption").append("&copy; Dalhousie University"); 
				break;
		case 7: $("#homePageSlideshowCaption").append("&copy; Dalhousie University"); 
				break;
		case 8: $("#homePageSlideshowCaption").append("&copy; Dalhousie University"); 
				break;
		case 9: $("#homePageSlideshowCaption").append("World's Largest Flume Tank<br>&copy; Marine Institute of Memorial University"); 
				break;
		case 10: $("#homePageSlideshowCaption").append("World's Largest Flume Tank<br>&copy; Marine Institute of Memorial University"); 
				break;
		case 11: $("#homePageSlideshowCaption").append("Indoor Training Tank and Environmental Theatre, with one of a kind high-fidelity helicopter underwater escape trainer<br>&copy; Marine Institute of Memorial University"); 
				break;
		case 12: $("#homePageSlideshowCaption").append("Offshore Safety and Survival Centre<br>&copy; Marine Institute of Memorial University"); 
				break;
		case 13: $("#homePageSlideshowCaption").append("Flume Tank<br>&copy; Marine Institute of Memorial University"); 
				break;
		case 14: $("#homePageSlideshowCaption").append("Proteomics Facility<br>Genomics and Proteomics (GaP) Facility - CREAIT Network <br>&copy; Memorial University"); 
				break;
		case 15: $("#homePageSlideshowCaption").append("Grenfell Telescope<br>&copy; Memorial University"); 
				break;
		case 16: $("#homePageSlideshowCaption").append("ICP Mass Spectrometer<br>&copy; Verschuren Centre for Sustainability in Energy and the Environment - Cape Breton University"); 
				break;
		case 17: $("#homePageSlideshowCaption").append("NMR Facility<br>Centre for Chemical Analysis, Research and Training (C-CART) – CREAIT Network<br>&copy; Memorial University"); 
				break;
		case 18: $("#homePageSlideshowCaption").append("Scanning Electron Microscope - Mineral Liberation Analysis Facility<br>MAF-IIC (Microanalysis Facility) – CREAIT Network<br>&copy; Memorial University"); 
				break;
		case 19: $("#homePageSlideshowCaption").append("Scanning Electron Microscope - Mineral Liberation Analysis Facility<br>MAF-IIC (Microanalysis Facility) – CREAIT Network<br>&copy; Memorial University"); 
				break;
		case 20: $("#homePageSlideshowCaption").append("Tow Tank, Engineering<br>&copy; Memorial University"); 
				break;
		case 21: $("#homePageSlideshowCaption").append("Scanning Electron Microscope - Mineral Liberation Analysis Facility<br>MAF-IIC (Microanalysis Facility) - CREAIT Network<br>&copy; Memorial University"); 
				break;
		case 22: $("#homePageSlideshowCaption").append("Laser Ablation ICP-MS Facility<br>MAF-IIC (Microanalysis Facility) – CREAIT Network<br>&copy; Memorial University"); 
				break;
		case 23: $("#homePageSlideshowCaption").append("Laser Ablation ICP-MS Facility<br>MAF-IIC (Microanalysis Facility) – CREAIT Network<br>&copy; Memorial University"); 
				break;
		case 24: $("#homePageSlideshowCaption").append("Transmission Electron Microscope:  JEOL 2011 (Scanning) Transmission Electron Microscope<br>&copy; University of New Brunswick"); 
				break;
		case 25: $("#homePageSlideshowCaption").append("Scanning Electron Microscope: JEOL 6400 SEM<br>&copy; University of New Brunswick"); 
				break;
		case 26: $("#homePageSlideshowCaption").append("Stable Isotope Laboratory<br>The Earth Resources Research and Analysis (TERRA) Facility – CREAIT Network<br>&copy; Memorial University"); 
				break;
		case 27: $("#homePageSlideshowCaption").append("Stable Isotope Laboratory<br>The Earth Resources Research and Analysis (TERRA) Facility – CREAIT Network<br>&copy; Memorial University"); 
				break;
		case 28: $("#homePageSlideshowCaption").append("Landmark Graphics Visualization Laboratory<br>Computing, Simulation and Landmark Visualization (CSLV) Facility – CREAIT Network<br>&copy; Memorial University"); 
				break;
		case 29: $("#homePageSlideshowCaption").append("Landmark Graphics Visualization Laboratory<br>Computing, Simulation and Landmark Visualization (CSLV) Facility – CREAIT Network<br>&copy; Memorial University"); 
				break;
		case 30: $("#homePageSlideshowCaption").append("Landmark Graphics Visualization Laboratory<br>Computing, Simulation and Landmark Visualization (CSLV) Facility – CREAIT Network<br>&copy; Memorial University"); 
				break;
		case 31: $("#homePageSlideshowCaption").append("Working on the payload<br>&copy; C-CORE"); 
				break;
		case 32: $("#homePageSlideshowCaption").append("Prepping the payload<br>&copy; C-CORE"); 
				break;
		case 33: $("#homePageSlideshowCaption").append("Confocal Microscope: Leica TCS-SP2 Confocal Laser Scanning Microscope<br>&copy; University of New Brunswick"); 
				break;
		case 34: $("#homePageSlideshowCaption").append("Electron Microprobe: JEOL 733 Microprobe<br>&copy; University of New Brunswick"); 
				break;
	}
}