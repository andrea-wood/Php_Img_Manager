var IMGTOOLS = (function Scope_STORE(IMGTOOLS, $) {

	var
		IMGTOOLS;
		
		IMGTOOLS = {
			debug :  true,
			configs : {
				'url' : 'handler.php',
			},
			msg : {
				// some return messages
			},
			html : {}
		};

		
	IMGTOOLS.init = function IMGTOOLS_init(html_target, params){
		this.html.parentItem = $(), // set parent item on the fly
		this.html.container = html_target.container,
		this.html.alert = html_target.container.find(".alert"),
		this.html.reload = html_target.container.find(".reload"),
		this.html.reloadIcon = html_target.container.find(".glp"),
		this.html.loader = html_target.container.find("#loader"),
		this.html.uploaderForm = html_target.container.find("form"),
		this.html.uploaderFile = html_target.container.find('input[type=file]'),
		this.html.pctname = html_target.container.find(".pctname"),
		this.html.removeBtn = html_target.container.find(".remove-item"),
		this.html.publishBtn = html_target.container.find(".publish-item"),
		this.html.unpublishBtn = html_target.container.find(".unpublish-item"),
		this.html.preview = html_target.container.find(".preview").find("img");
		this.form();
		this.upload();
		this.remove();
        this.unpublish();
        this.publish();
	};


	IMGTOOLS.submit = function IMGTOOLS_submit(data, type){

			this.loader("show");

			$.ajax({
			  	url  	 : IMGTOOLS.configs.url,
				data 	 : data,
				type 	 : "POST",
				dataType : "json",
				contentType : false,
				processData : false,
				cache :  false

			}).done(function(json){
				if(json.success){
					if(json.success.data.upload_type == "preview"){
						IMGTOOLS.html.pctname.val(json.success.data.name + json.success.data.type);
						IMGTOOLS.html.preview.attr("src",json.success.data.rel_th_path).parent().show();
					} else if(json.success.data.type == "remove"){
						IMGTOOLS.html.parentItem.remove();
					} else {
						IMGTOOLS.html.uploaderForm.remove();
						IMGTOOLS.alert("success", json.success.msg);
					}
					
				} else if(json.error){
					IMGTOOLS.alert("error", json.error.msg, json.error.code);
				}

			}).fail(function(jqXHR, textStatus) {

				console.log("Request failed: " + textStatus);

			}).then(function(){
				IMGTOOLS.html.parentItem = $();
				IMGTOOLS.loader("hide");
			
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
    		IMGTOOLS.submit(data);

		});	
	};

	IMGTOOLS.remove = function IMGTOOLS_remove(){
		IMGTOOLS.html.removeBtn.on("click", function(e){
			e.preventDefault();
			IMGTOOLS.html.parentItem = $(this).closest('tr');
			var rid = IMGTOOLS.html.parentItem.data("id");
			var title = IMGTOOLS.html.parentItem.find('h3').text();
			var data = new FormData();
			data.append("submit", "remove");
			data.append("id", rid);
			IMGTOOLS.html.parentItem.addClass("danger");
			var confirm = IMGTOOLS.alert("remove", "Are you sure you want to remove " + title + " ?");
			if(confirm){
				IMGTOOLS.submit(data);
			} else {
				IMGTOOLS.html.parentItem.removeClass("danger");
			}
		});	
	};

	IMGTOOLS.publish = function IMGTOOLS_publish(){
		IMGTOOLS.html.publishBtn.on("click", function(e){
			e.preventDefault();
			IMGTOOLS.html.parentItem = $(this).closest('tr');
			var uid = IMGTOOLS.html.parentItem.data("id");
			var title = IMGTOOLS.html.parentItem.find('h3').text();
			var data = new FormData();
			data.append("submit", "publish");
			data.append("id", uid);
			var confirm = IMGTOOLS.alert("remove", "Are you sure you want to pulish " + title + " ?");
			if(confirm){
				IMGTOOLS.html.parentItem.removeClass("warning");
				IMGTOOLS.submit(data);
			} 
		});	
	};

	IMGTOOLS.unpublish = function IMGTOOLS_unpublish(){
		IMGTOOLS.html.unpublishBtn.on("click", function(e){
			e.preventDefault();
			IMGTOOLS.html.parentItem = $(this).closest('tr');
			var uid = IMGTOOLS.html.parentItem.data("id");
			var title = IMGTOOLS.html.parentItem.find('h3').text();
			var data = new FormData();
			data.append("submit", "unpublish");
			data.append("id", uid);
			IMGTOOLS.html.parentItem.addClass("warning");
			var confirm = IMGTOOLS.alert("remove", "Are you sure you want to unpulish " + title + " ?");
			if(confirm){
				IMGTOOLS.submit(data);
			} else {
				IMGTOOLS.html.parentItem.removeClass("warning");
			}
		});	
	};

	IMGTOOLS.alert = function IMGTOOLS_alert(type, msg, code){
		var classType = "";
		var msg = msg;
		switch(type){
			case "error":
				msg = (IMGTOOLS.debug) ? msg + " Code: " + code : msg;

				$(IMGTOOLS.html.alert).find("p").text(msg);
				classType = "alert-danger";
				$(IMGTOOLS.html.alert).addClass(classType);
				break;
			case "success":
				$(IMGTOOLS.html.alert).find("p").text(msg);
				classType = "alert-success";
				IMGTOOLS.html.reloadIcon.show();
				IMGTOOLS.reload();
				$(IMGTOOLS.html.alert).addClass(classType);
				break;
			case "remove":
				return confirm(msg);
				break;
		}	
		
	};

	IMGTOOLS.reload = function IMGTOOLS_reload(){
		IMGTOOLS.html.reload.on("click", function(e){
			e.preventDefault;
			window.location.reload();
		});
	};

	IMGTOOLS.loader = function IMGTOOLS_loader(event){
		this.html.loader.modal({
			keyboard: false,
			backdrop: "static",
		});
		this.html.loader.modal(event);
	};

	return IMGTOOLS;

}( IMGTOOLS || {}, jQuery));