<script>
	$.ajax({
			type: 'POST',
			url: 'get_all_photo.php',
			data: {},
			success: function(response){
				console.log(response);
				document.getElementById("photo-list").innerHTML = '';
				var imgs = JSON.parse(response);
				var list = document.getElementById("photo-list");
				for(var i = 0; i < imgs.length; i++)
				{
					var container = document.createElement("div");
					var img = document.createElement("img");
					img.src = "http://localhost:8080/images/" + imgs[imgs.length - 1 - i]['image_id'] + ".png";
					img.classList.add("image");
					container.classList.add("img-container");
					container.appendChild(img);
					list.appendChild(container);
				}
			}
	});
</script>
