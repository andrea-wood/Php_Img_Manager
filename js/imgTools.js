var IMGTOOLS = (function Scope_STORE(IMGTOOLS, $) {

	var
		IMGTOOLS;
		
		IMGTOOLS = {
			configs : {
				'url' : 'handler.php',
			},
			msg : {
				// some return messages
			},
			html : {}
		};

		
	IMGTOOLS.init = function IMGTOOLS_init(html_target, params){
		this.html.container = html_target.container,
		this.html.alert = html_target.container.find(".alert"),
		this.html.reload = html_target.container.find(".reload"),
		this.html.loader = html_target.container.find("#loader"),
		this.html.uploaderForm = html_target.container.find("form"),
		this.html.uploaderFile = html_target.container.find('input[type=file]'),
		this.html.preview = html_target.container.find(".preview");
        this.form();
		this.upload();
	};


	IMGTOOLS.submit = function IMGTOOLS_submit(data, type){

			this.loader();

			$.ajax({
			  	url  	 : IMGTOOLS.configs.url,
				data 	 : data,
				type 	 : "POST",
				contentType : false,
				processData : false,
				cache :  false

			}).done(function(json){
				var parsedJson = JSON.parse(json);
				if(parsedJson.response){
					if(parsedJson.data.upload_type == "preview"){
						IMGTOOLS.html.preview.find("img").attr("src",parsedJson.data.rel_th_path).parent().show();
					} else {
						IMGTOOLS.html.uploaderForm.remove();
						IMGTOOLS.alert();
					}
					this.loader();
				}
			});
	};

	IMGTOOLS.form = function IMGTOOLS_form(){
		IMGTOOLS.html.uploaderForm.on("submit", function(e){
			e.preventDefault();
			IMGTOOLS.submit(new FormData(this));
		});
		
	};

	IMGTOOLS.upload = function IMGTOOLS_upload(){
		IMGTOOLS.html.uploaderFile.on("change", function(e){

			var data = new FormData();
			$.each(event.target.files, function(key, value){
				data.append(key, value);
			});
    		IMGTOOLS.submit(data, "preview");

		});	
	};

	IMGTOOLS.alert = function IMGTOOLS_alert(msg){
		$(".glp").show();
		$(IMGTOOLS.html.alert).text("DONE!").addClass("alert-success");
		IMGTOOLS.reload();
	};

	IMGTOOLS.reload = function IMGTOOLS_reload(){
		IMGTOOLS.html.reload.on("click", function(e){
			e.preventDefault;
			window.location.reload();
		});
	};

	IMGTOOLS.loader = function IMGTOOLS_loader(){
		this.html.loader.modal({
			keyboard: false,
			backdrop: "static",
		});
		this.html.loader.modal("toggle");
	};

	return IMGTOOLS;

}( IMGTOOLS || {}, jQuery));