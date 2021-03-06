<script>
	function load_comment(img_id)
	{
		let comment_list = document.getElementById(img_id).nextElementSibling.children[2];
		comment_list.innerHTML = '';
		$.ajax({
			type: 'POST',
			url: 'fetch_comment.php',
			data: {img_id : img_id},
			success: function(response_json)
			{
				var response = JSON.parse(response_json);
				for (let i = response.length - 1; i >=0 ; i--)
				{
					var comment = document.createElement("div");
					comment.classList.add('comment');
					comment.innerHTML = response[i]['comment'];
					comment_list.appendChild(comment);
				}
			}
		});
	}
	window.onresize = function() {
		let comment_section = document.getElementsByClassName('comment-section');
		for (let i = 0; i < comment_section.length; i++)
		{
			comment_section[i].style.top = comment_section[i].previousSibling.height + 20;
			comment_section[i].children[2].style.height = document.getElementsByClassName('img-container')[0].offsetHeight - (parseInt(comment_section[i].style.top, 10) + document.getElementsByClassName('img-container')[0].offsetWidth * 0.05 + 25);
		}
	};
	$.ajax({
			type: 'POST',
			url: 'get_all_photo.php',
			data: {},
			success: function(response){
				document.getElementById("photo-list").innerHTML = '';
				var imgs = JSON.parse(response);
				var list = document.getElementById("photo-list");
				for(var i = 0; i < imgs.length; i++)
				{
					var container = document.createElement("div");
					var img = document.createElement("img");
					var localIP = <?php require 'config/database.php'; echo "\"$localIP\""; ?>;
					img.src = "http://" + localIP + ":8080/images/" + imgs[imgs.length - 1 - i]['image_id'] + ".png";
					img.classList.add("image");
					img.id = imgs[imgs.length - 1 - i]['image_id'];
					container.classList.add("img-container");
					container.appendChild(img);
					list.appendChild(container);

					var comment_section = document.createElement("div");
					comment_section.classList.add("comment-section");

					var comment_list = document.createElement("div");
					comment_list.classList.add("comment-list");

					var form = document.createElement("form");
					form.classList.add("comment-form");

					var like_btn = document.createElement("img");
					like_btn.classList.add("like-btn");
					like_btn.onclick = function(){
						let liked = 0;
						if (this.getAttribute('src') == "like_btn_liked.png")
							liked = 1;
						let img_id = this.parentElement.previousElementSibling.id;
						$.ajax({
							type: 'POST',
							url: 'add_like.php',
							data: {img_id: img_id, liked: liked},
							success: function(response){
								if(response == '-1')
									window.location.href = "login.php";
							}
						})
						if(liked == 0)
							this.src = "like_btn_liked.png";
						else
							this.src = "like_btn.png";
					};

					var comment = document.createElement("input");
					comment.classList.add("new-comment");
					comment.type = "text";
					comment.maxLength = "256";
					img.onload = function() {
						this.nextSibling.style.top = this.height + 20;
						this.nextElementSibling.children[2].style.height = document.getElementsByClassName('img-container')[0].offsetHeight - (parseInt(this.nextSibling.style.top, 10) + document.getElementsByClassName('img-container')[0].offsetWidth * 0.05 + 25);
						}

					var comment_button = document.createElement("input");
					comment_button.classList.add("comment-button");
					comment_button.type = "button";
					comment_button.value = "comment";
					comment_button.onclick = function() {
						const new_com = this.previousElementSibling.value;
						const image_id = this.parentElement.parentElement.previousElementSibling.id;
						$.ajax({
							type: 'POST',
							url: 'add_comment.php',
							data: {com : new_com, img_id : image_id},
							success: function(response)
							{
								if(response == '-1')
									window.location.href = "login.php";
								load_comment(image_id);
							}
						});
					};
					form.appendChild(comment);
					form.appendChild(comment_button);
					comment_section.appendChild(like_btn);
					comment_section.appendChild(form);
					comment_section.appendChild(comment_list);
					container.appendChild(comment_section);
					load_comment(img.id);
				}
			}
	})
	.then(function()
	{
	$.ajax({
		type: 'POST',
		url: 'get_likes.php',
		data: {},
		success: function(response)
		{
			let likes_btn = document.getElementsByClassName('like-btn');
			let likes = JSON.parse(response);
			let img_id = 0;
			if (likes.length != 0)
				img_id = likes[0]['image_id'];
			let j = 0;
			for(let i = 0; i < likes_btn.length; i++)
			{
				if (img_id == likes_btn[i].parentElement.previousElementSibling.id)
				{
					likes_btn[i].src = 'like_btn_liked.png';
					while (++j < likes.length)
					{
						img_id = likes[j]['image_id'];
						if (img_id != likes[j - 1]['image_id'])
							break;
					}
				}
				else
					likes_btn[i].src = 'like_btn.png';
			}
		}
	})});
</script>
