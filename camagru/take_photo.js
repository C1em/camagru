async function getMedia(constraints)
{
	let stream = null;
	try {
		stream = await navigator.mediaDevices.getUserMedia(constraints);
		var video = document.querySelector('video');
		if ("srcObject" in video)
			video.srcObject = stream;
		else
			video.src = window.URL.createObjectURL(stream);
		video.onloadedmetadata = function(e)
		{
			video.play();
		};
	} catch(err) {
			console.log(err.name + ": " + err.message);
	}
}

function take_photo()
{
	let stickers = document.getElementsByClassName("sticker");
	let stickers_pos = [];
	let cam_left_pos = document.querySelector('video').getBoundingClientRect().left;
	let cam_top_pos = document.querySelector('video').getBoundingClientRect().top;
	let canvas = document.querySelector('canvas');
	let j = 0;
	for (var i = 0; i < stickers.length; i++)
	{
		if (stickers[i].getBoundingClientRect().left < cam_left_pos
		|| stickers[i].getBoundingClientRect().top < cam_top_pos)
			continue;
		stickers_pos[j++] = stickers[i].getBoundingClientRect().left - cam_left_pos;
		stickers_pos[j++] = stickers[i].getBoundingClientRect().top - cam_top_pos;
		canvas.width = stickers[i].getBoundingClientRect().width;
		canvas.height = stickers[i].getBoundingClientRect().height;
		stickers_pos[j++] = canvas.width;
		stickers_pos[j++] = canvas.height;
		canvas.getContext('2d').drawImage(stickers[i], 0, 0, canvas.width, canvas.height);
		stickers_pos[j++] = canvas.toDataURL();
		canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
	}
	// put sticker pos if in webcam then send pos relative to webcam and the sticker image

	let stickers_pos_json = JSON.stringify(stickers_pos);
	canvas.height = video.getBoundingClientRect().height;
	canvas.width = video.getBoundingClientRect().width;
	canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
	let shot = canvas.toDataURL();
	canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
	$.ajax({
			type: 'POST',
			url: 'save_photo.php',
			data: {image: shot, stickers_pos : stickers_pos_json},
			success: function(response){
				print_side_photos();
			}
		});
}

function print_side_photos()
{
	$.ajax({
			type: 'POST',
			url: 'get_photo.php',
			data: {},
			success: function(response){
				document.getElementById("side").innerHTML = '';
				var imgs = JSON.parse(response);
				var sideElem = document.getElementById("side");
				for(var i = 0; i < imgs.length; i++)
				{
					let img_container = document.createElement('div');
					img_container.classList.add('img-container');
					let img = document.createElement("img");
					let cross = document.createElement('div');
					cross.innerHTML = '&times;';
					cross.classList.add('cross');
					cross.onclick = del_img;
					//localIP is set in take_photo.php
					img.src = "http://" + localIP + ":8080/images/" + imgs[imgs.length - 1 - i]['image_id'] + ".png";
					img.classList.add('side-img');
					img.id = imgs[imgs.length - 1 - i]['image_id'];
					img_container.appendChild(img);
					img_container.appendChild(cross);
					sideElem.appendChild(img_container);
				}
			}
	});
}

function upload_photo()
{
	const file = document.querySelector('input[type=file]').files[0];
	const reader = new FileReader();
	if (file)
	{
		reader.readAsDataURL(file);
		reader.onload = function(e)
		{
			$.ajax({
				type: 'POST',
				url: 'save_photo.php',
				data: {image: reader.result},
				success: function(response){
					print_side_photos();
				}
			});
		}
	}
}

function del_img()
{
	let img_id = this.previousElementSibling.id;
	$.ajax({
		type: 'POST',
		url: 'del_photo.php',
		data: {img_id : img_id},
		success: function(response){
			print_side_photos();
		}
	});
}
