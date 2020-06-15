var stickers = document.getElementsByClassName("sticker");
for (var i = 0; i < stickers.length; i++)
	dragElement(stickers[i]);

function dragElement(elmnt)
{
	var pos1 = 0, pos2 = 0;
	elmnt.onmousedown = dragMouseDown;

	function dragMouseDown(e)
	{
		e.preventDefault();
		pos1 = e.clientX;
		pos2 = e.clientY;
		document.onmouseup = closeDragElement;
		document.onmousemove = elementDrag;
	}

	function elementDrag(e)
	{
		e.preventDefault();
		elmnt.style.top = (elmnt.offsetTop - pos2 + e.clientY) + "px";
		elmnt.style.left = (elmnt.offsetLeft - pos1 + e.clientX) + "px";
		pos1 = e.clientX;
		pos2 = e.clientY;
	}

	function closeDragElement()
	{
		document.onmouseup = null;
		document.onmousemove = null;
	}
}
