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
		this.html.uploaderForm = html_target.container.find("form");
		this.html.uploaderFile = html_target.container.find('input[type=file]');
		this.html.responsediv = html_target.container.find(".response");
        this.form();
		this.upload();
	};


	IMGTOOLS.submit = function IMGTOOLS_submit(data, type){
			$.ajax({
			  	url  	 : IMGTOOLS.configs.url,
				data 	 : data,
				type 	 : "POST",
				contentType : false,
				processData : false,
				cache :  false

			}).done(function(json){
				var parsed = JSON.parse(json);
				if(parsed.response){
					if(parsed.data.upload_type == "preview"){
						$(IMGTOOLS.html.responsediv).find("img").attr("src", parsed.data.rel_th_path).parent().show();
					} else {
						$(IMGTOOLS.html.uploaderForm).remove();
					}
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
		$(IMGTOOLS.html.uploaderFile).on("change", function(e){

			var data = new FormData();
			$.each(event.target.files, function(key, value){
				data.append(key, value);
			});
    		IMGTOOLS.submit(data, "preview");

		});	
	};

	return IMGTOOLS;

}( IMGTOOLS || {}, jQuery));