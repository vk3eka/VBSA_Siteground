function CookieDef(expires,path,domain,secure) {
  this.secure = secure;
  this.path = path;
  this.domain = domain;
  this.getValue = getCookie;
  this.setValue = setCookie;
  this.expire = deleteCookie;
  if (expires == 0) {
    this.expires = "";
  } else {
    var today_date = new Date();
	 var expire_seconds = today_date.getTime() + (expires * 24 * 60 * 60  * 1000);
    today_date.setTime(expire_seconds);
    this.expires = today_date.toGMTString();
  }
}
function getCV(offset) {
  var endstr = document.cookie.indexOf(";", offset);
  if (endstr == -1) endstr = document.cookie.length;
  return unescape(document.cookie.substring(offset, endstr)).replace(/\\+/g," ");
}
function getCookie(name) {
  var arg = name.toUpperCase() + "=";
  var arg2 = name.replace(/_/g,"%5F").toUpperCase() + "=";
  var alen = arg.length;
  var alen2 = arg2.length;
  var clen = document.cookie.length;
  var i = 0;
  while (i < clen) {
    var j = i + alen;
    var j2 = i + alen2;
    if (document.cookie.substring(i, j).toUpperCase() == arg)
      return getCV(j);
    if (document.cookie.substring(i, j2).toUpperCase() == arg2)
      return getCV(j2);
    i = document.cookie.indexOf(" ", i) + 1;
    if (i == 0) break;
  }
  return "";
}
function setCookie(name,value)
{
  document.cookie = name.toUpperCase() + "=" + escape(value) +
    ((this.expires == "") ? "" : ("; expires=" + this.expires)) +
    ((this.path == "") ? "" : ("; path=" + this.path)) +
    ((this.domain == "") ? "" : ("; domain=" + this.domain)) +
    ((this.secure == true) ? "; secure" : "");
}
function deleteCookie(name) {
  document.cookie = name + "=" + "" + "; expires=Thu,01-Jan-70 00:00:01 GMT";
}
function getID(formElement, x)  {
  var retID = String(x);
  if (formElement.id) {
	  retID = String(formElement.id);
  }
  else if (formElement.name)  {
	  retID = String(formElement.name);
  }
  return retID;
}

function cookieFormToString(cookieobj,theForm,SaveAs)  {
  var theFormElements = theForm.elements;
  var theCookieString = "";
  var theCookieSeparator = "|?";
  for (var x=0; x<theFormElements.length; x++)  {
    if (theFormElements[x].tagName=="TEXTAREA")  {
      if (theCookieString!="")  {
	    theCookieString += theCookieSeparator;
	  }
	  theCookieString += getID(theFormElements[x],x) + "=" + theFormElements[x].value;
    }
	else if (theFormElements[x].tagName=="SELECT")  {
      if (theCookieString != "")  {
	    theCookieString += theCookieSeparator;
	  }
	  for (var y=0; y<theFormElements[x].options.length; y++)  {
	    if (theFormElements[x].options[y].selected) {
	      theCookieString += getID(theFormElements[x],x) + "=" + theFormElements[x].options[y].value+theCookieSeparator;
		}
	  }
    }
	else  {
	  theType = "text";
	  if (theFormElements[x].type) {
	    theType = theFormElements[x].type.toLowerCase();
	  }
	  if (theType == "text")  {
        if (theCookieString!="")  {
	      theCookieString += theCookieSeparator;
	    }
	    theCookieString += getID(theFormElements[x],x) + "=" + theFormElements[x].value;
	  }
	  if (theType == "hidden")  {
        if (theCookieString!="")  {
	      theCookieString += theCookieSeparator;
	    }
	    theCookieString += getID(theFormElements[x],x) + "=" + theFormElements[x].value;
	  }
	  if (theType == "checkbox")  {
	    if (theFormElements[x].checked)  {
          if (theCookieString!="")  {
	        theCookieString += theCookieSeparator;
	      }
	      theCookieString += getID(theFormElements[x],x) + "=" + theFormElements[x].value;
		}
	  }
	  if (theType == "radio")  {
	    if (theFormElements[x].checked)  {
          if (theCookieString!="")  {
	        theCookieString += theCookieSeparator;
	      }
	      theCookieString += getID(theFormElements[x],x) + "=" + theFormElements[x].value;
		}
	  }
    }
  }
  cookieobj.setValue(SaveAs, theCookieString);
}


function findValInString(theString,theVal,theSep)  {
  var retVal = "";
  var searchStr = theSep + theString + theSep;
  var findStr = theSep + theVal + "=";
  if (searchStr.indexOf(findStr) >= 0)  {
    retVal = searchStr.substring(searchStr.indexOf(findStr)+findStr.length);
	retVal = retVal.substring(0,retVal.indexOf(theSep));
  }
  return retVal;
}

function findMatchInString(theString,theMatch,theSep)  {
  var searchStr = theSep + theString + theSep;
  var findStr = theSep + theMatch + theSep;
  if (searchStr.indexOf(findStr) >= 0)  {
    return true;
  }
  return false;
}


function cookieFormFromString(cookieobj,theForm,SaveAs)  {
  var cookieVal = cookieobj.getValue(SaveAs);
  var theFormElements = theForm.elements;
  var theCookieSeparator = "|?";
  for (var x=0; x<theFormElements.length; x++)  {
    if (theFormElements[x].tagName=="TEXTAREA")  {
	  var findID = getID(theFormElements[x], x);
	  var findVal = findValInString(cookieVal,findID,theCookieSeparator);
	  if (findVal != "")  {
	    theFormElements[x].value = findVal;
	  }
    }
	else if (theFormElements[x].tagName=="SELECT")  {
	  var findID = getID(theFormElements[x], x);
	  for (var y=0; y<theFormElements[x].options.length; y++)  {
        if (findMatchInString(cookieVal,findID+"="+theFormElements[x].options[y].value,theCookieSeparator))  {
		  theFormElements[x].options[y].selected = true;
		}
	  }
    }
	else  {
	  theType = "text";
	  if (theFormElements[x].type) {
	    theType = theFormElements[x].type.toLowerCase();
	  }
	  if (theType == "text")  {
        var findID = getID(theFormElements[x], x);
	    var findVal = findValInString(cookieVal,findID,theCookieSeparator);
	    if (findVal != "")  {
	      theFormElements[x].value = findVal;
	    }
	  }
	  if (theType == "hidden")  {
        var findID = getID(theFormElements[x], x);
	    var findVal = findValInString(cookieVal,findID,theCookieSeparator);
	    if (findVal != "")  {
	      theFormElements[x].value = findVal;
	    }
	  }
	  if (theType == "checkbox")  {
	    var findID = getID(theFormElements[x], x);
        if (findMatchInString(cookieVal,findID+"="+theFormElements[x].value,theCookieSeparator))  {
		  theFormElements[x].checked = true;
		}
	  }
	  if (theType == "radio")  {
        var findID = getID(theFormElements[x], x);
        if (findMatchInString(cookieVal,findID+"="+theFormElements[x].value,theCookieSeparator))  {
		  theFormElements[x].checked = true;
		}
	  }
    }
  }
}

function daysToExpire(expiresDays)  {
  var today_date = new Date();
  var expire_seconds = today_date.getTime() + (expiresDays * 24 * 60 * 60  * 1000);
  today_date.setTime(expire_seconds);
  return today_date.toGMTString();
}

WA_CookieObj = new CookieDef(30,"/","","0");