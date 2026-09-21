function CALCaddChar(input, character) 
{
	if(input.value == null || input.value == "0")
	{
		input.value = character;
	}
	else
	{
		input.value += character;
	}
}

function CALCcos(form) 
{
	form.display.value = Math.cos(form.display.value);
}

function CALCsin(form) 
{
	form.display.value = Math.sin(form.display.value);
}

function CALCtan(form) 
{
	form.display.value = Math.tan(form.display.value);
}

function CALCsqrt(form) 
{
	form.display.value = Math.sqrt(form.display.value);
}

function CALCln(form) 
{
	form.display.value = Math.log(form.display.value);
}

function CALCexp(form) 
{
	form.display.value = Math.exp(form.display.value);
}

function CALCsqrt(form) 
{
	form.display.value = Math.sqrt(form.display.value);
}

function CALCdeleteChar(input) 
{
	input.value = input.value.substring(0, input.value.length - 1)
}

function CALCchangeSign(input) 
{
	if(input.value.substring(0, 1) == "-")
	{
		input.value = input.value.substring(1, input.value.length);
	}
	else
	{
		input.value = "-" + input.value;
	}
}

function CALCcompute(form)  
{
	form.display.value = eval(form.display.value);
}

function CALCsquare(form)  
{
	form.display.value = eval(form.display.value) * eval(form.display.value);
}

function CALCcheckNum(str)  
{
	for (var i = 0; i < str.length; i++) 
	{
		var ch = str.substring(i, i+1);
		if (ch < "0" || ch > "9") 
		{
			if (ch != "/" && ch != "*" && ch != "+" && ch != "-" && ch != "." && ch != "(" && ch!= ")") 
			{
				alert("invalid entry!");
				return false;
			}
		}
	}
	
	return true;
}
