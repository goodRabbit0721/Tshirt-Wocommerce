/* plugin upload multiple files */
/*	File type
	* PNG		image/png
	* GIF		image/gif
	* JPG		image/jpeg
	* BUP		image/bmp
	* TIFF		image/tiff
	* PDF		application/pdf
	* EPS, AI 	application/postscript
	
*/
(function () {
	var filesUpload = document.getElementById("files-upload"),
		dropArea 	= document.getElementById("drop-area"),
		fileList 	= document.getElementById("dag-files-images"),
		fileType 	= ["image/png", "image/gif", "image/jpeg", "image/bmp", "image/tiff", "application/pdf", "application/postscript"],
		maxsize		= 10; //MB
	function uploadFile (file) {		
		var check = fileType.indexOf(file.type);
		if(check == -1)
		{
			alert('Accepted File Types (Max file size: 10MB)\nImage .jpg, .jpeg, .png, .gif, .bmp, .tiff\nAdobe Acrobat .pdf\nAdobe Illustrator .ai');
			return false;
		}
		if(file.size > 1048576 * maxsize){	//1048576 = 1MB
			alert('Too big file (max filesize exceeded '+maxsize+'MB)');
			return false;
		}
		var span = document.createElement("span"),			
			img,
			progressBarContainer = document.createElement("div"),
			progressBar = document.createElement("div"),
			reader,
			xhr,
			fileInfo;
		span.className = 'view-thumb';					
		
		/*
			If the file is an image and the web browser supports FileReader,
			present a preview in the file list
		*/
		if (typeof FileReader !== "undefined" && (/image/i).test(file.type)) {
			img = document.createElement("img");
			span.appendChild(img);
			reader = new FileReader();
			reader.onload = (function (theImg) {
				return function (evt) {
					theImg.src = evt.target.result;					
				};
			}(img));
			reader.readAsDataURL(file);
		}		
		progressBarContainer.className = "progress-bar-container";
		progressBar.className = "progress-bar";
		progressBarContainer.appendChild(progressBar);
		span.appendChild(progressBarContainer);
		
		// Uploading - for Firefox, Google Chrome and Safari
		xhr = new XMLHttpRequest();
		
		// Update progress bar
		xhr.upload.addEventListener("progress", function (evt) {
			if (evt.lengthComputable) {
				progressBar.style.width = (evt.loaded / evt.total) * 100 + "%";				
			}
			else {
				// No data to calculate on
			}
		}, false);
		
		// File uploaded
		xhr.addEventListener("load", function () {
			progressBarContainer.className += " uploaded";
			progressBar.innerHTML = "Uploaded!";			
		}, false);
		
		var path = $d('#media-path').html();
		var url = admin_url(base + 'index.php/media/upload/&folder='+path);			
		
		xhr.open("post", url, true);
		
		xhr.onload = function() {
			if (this.responseText != '' && this.responseText.indexOf('</div>') == -1)
			{
				var media 					= eval('('+this.responseText+')');
				if (typeof(media.status) != 'undefined' && media.status == '1')
				{
					img.setAttribute('src',mainURL + media.file.url);
					img.setAttribute('alt', media.file.file_name);
					span.setAttribute('onclick', 'dagFiles.file.select(this)');
					span.setAttribute('title', media.file.file_name);
					span.setAttribute('data-filename', media.file.file_name);
				}
				else
				{
					alert(media.msg);
				}
			}           
        };
		
		var formData = new FormData();  
        formData.append('myfile', file); 
		xhr.send(formData);		
		fileList.appendChild(span);
	}
	
	function traverseFiles (files) {
		if (typeof files !== "undefined") {
			for (var i=0, l=files.length; i<l; i++) {
				uploadFile(files[i]);
			}
		}
		else {
			fileList.innerHTML = "No support for the File API in this web browser";
		}	
	}
	
	filesUpload.addEventListener("change", function () {
		traverseFiles(this.files);
	}, false);
	
	dropArea.addEventListener("dragleave", function (evt) {
		var target = evt.target;
		
		if (target && target === dropArea) {
			this.className = "";
		}
		evt.preventDefault();
		evt.stopPropagation();
	}, false);
	
	dropArea.addEventListener("dragenter", function (evt) {
		this.className = "over";
		evt.preventDefault();
		evt.stopPropagation();
	}, false);
	
	dropArea.addEventListener("dragover", function (evt) {
		evt.preventDefault();
		evt.stopPropagation();
	}, false);
	
	dropArea.addEventListener("drop", function (evt) {
		traverseFiles(evt.dataTransfer.files);
		this.className = "";
		evt.preventDefault();
		evt.stopPropagation();
	}, false);										
})();