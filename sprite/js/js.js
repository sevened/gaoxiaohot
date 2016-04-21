document.onkeydown = function(evt){ 
	evt = evt ||window.event; 
	var key=evt.which||evt.keyCode; 
	if (key == 37)
	{
		var pre = document.getElementById('pre').getElementsByTagName("a")[0];
		if(pre!=null)
		{
			pre.click();
		}
		else
		{
			pre = document.getElementById("pre");
			if(pre!=null){pre.click();}
		}
	}
	if (key == 39)
	{		
		var next = document.getElementById('next').getElementsByTagName("a")[0];
		if(next!=null)
		{
			next.click();
		}
		else
		{
			next = document.getElementById('next');
			if(next!=null){next.click();}
		}
	}
}; 

function postDigg(ftype,aid)
{
	var taget_obj = document.getElementById(aid);
	var saveid = GetCookie('diggid');
	if(saveid != null)
	{
		var saveids = saveid.split(',');
		var hasid = false;
		saveid = '';
		j = 1;
		for(i=saveids.length-1;i>=0;i--)
		{
			if(saveids[i]==aid && hasid) continue;
			else {
				if(saveids[i]==aid && !hasid) hasid = true;
				saveid += (saveid=='' ? saveids[i] : ','+saveids[i]);
				j++;
				if(j==20 && hasid) break;
				if(j==19 && !hasid) break;
			}
		}
		if(hasid) { alert("您已经顶过该帖，请不要重复顶帖 ！"); return; }
		else saveid += ','+aid;
		SetCookie('diggid',saveid,1);
	}
	else
	{
		SetCookie('diggid',aid,1);
	}
	myajax = new DedeAjax(taget_obj,false,false,'','','');
	var url = "/plus/digg_ajax.php?action="+ftype+"&id="+aid;
	myajax.SendGet2(url);
}