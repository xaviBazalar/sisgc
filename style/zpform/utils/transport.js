/**
 * \file transport.js
 * Zapatec Transport library.
 * Used to fetch data from the server, parse and serialize XML and JSON data.
 *
 * Copyright (c) 2004-2005 by Zapatec, Inc.
 * http://www.zapatec.com
 * 1700 MLK Way, Berkeley, California,
 * 94709, U.S.A.
 * All rights reserved.
 *
 * $Id: transport.js 1193 2005-12-13 22:46:41Z alex $
 */

if (typeof Zapatec == 'undefined') {
  /**
   * Namespace definition.
   */
  Zapatec = {};
}

/**
 * Namespace definition.
 */
Zapatec.Transport = {};

// Determine most current versions of ActiveX objects available
if (typeof ActiveXObject != 'undefined') {

  /**
   * \internal String variable with most current version of XMLDOM ActiveX
   * object name available.
   */
  Zapatec.Transport.XMLDOM = null;

  /**
   * \internal String variable with Most current version of XMLHTTP ActiveX
   * object name available.
   */
  Zapatec.Transport.XMLHTTP = null;

  /**
   * \internal Returns first available ActiveX object name from the given list.
   *
   * \param arrVersions [object] list of ActiveX object names to test.
   * \return [string] first available ActiveX object name or null.
   */
  Zapatec.Transport.pickActiveXVersion = function(arrVersions) {
    for (var iVn = 0; iVn < arrVersions.length; iVn++) {
      try {
        var objDocument = new ActiveXObject(arrVersions[iVn]);
        // If it gets to this point, the string worked
        return arrVersions[iVn];
      } catch (objException) {};
    }
    return null;
  };

  // Get most current version of XMLDOM ActiveX object
  Zapatec.Transport.XMLDOM = Zapatec.Transport.pickActiveXVersion([
    'Msxml2.DOMDocument.4.0',
    'Msxml2.DOMDocument.3.0',
    'MSXML2.DOMDocument',
    'MSXML.DOMDocument',
    'Microsoft.XMLDOM'
  ]);

  // Get most current version of XMLHTTP ActiveX object
  Zapatec.Transport.XMLHTTP = Zapatec.Transport.pickActiveXVersion([
    'Msxml2.XMLHTTP.4.0',
    'MSXML2.XMLHTTP.3.0',
    'MSXML2.XMLHTTP',
    'Microsoft.XMLHTTP'
  ]);

  // We don't need this any more
  Zapatec.Transport.pickActiveXVersion = null;

}

/**
 * Creates cross browser XMLHttpRequest object.
 *
 * \return [object] new XMLHttpRequest object.
 */
Zapatec.Transport.createXmlHttpRequest = function() {
  if (typeof XMLHttpRequest != 'undefined') {
    return new XMLHttpRequest();
  }
  if (typeof ActiveXObject != 'undefined') {
    try {
      return new ActiveXObject(Zapatec.Transport.XMLHTTP);
    } catch (objException) {};
  }
  return null;
};

/**
 * Fetches specified URL using new XMLHttpRequest object.
 *
 * Asynchronous mode is used because it is safer and there is no risk of having
 * your script hang in case of network problem.
 *
 * When request is completed, one of provided callback functions is called:
 * onLoad on success or onError on error.
 *
 * onLoad callback function receives XMLHttpRequest object as argument and may
 * use its various properties like responseText, responseXML, etc.
 *
 * onError callback function receives following object:
 * {
 *   errorCode: server status number (404, etc.) [number],
 *   errorDescription: human readable error description [string]
 * }
 *
 * Note: Some browsers implement caching for GET requests. Caching can be
 * prevented by adding 'r=' + Math.random() parameter to URL.
 *
 * If you use POST method, content argument should be something like
 * 'var1=value1&var2=value2' with urlencoded values. If you wish to send other
 * content, set appropriate contentType. E.g. 'multipart/form-data', 'text/xml',
 * etc.
 *
 * Server response should not contain non-ASCII characters.
 *
 * \param objArgs [object] associative array with arguments:
 * {
 *   url: relative or absolute URL to fetch [string],
 *   method: method ('GET', 'POST', 'HEAD', 'PUT') [string] (optional),
 *   contentType: content type when using POST [string] (optional),
 *   content: postable string or DOM object data when using POST
 *   [string or object] (optional),
 *   onLoad: function reference to call on success [function] (optional),
 *   onError: function reference to call on error [function] (optional),
 *   username: username [string] (optional),
 *   password: password [string] (optional)
 * }
 */
Zapatec.Transport.fetch = function(objArgs) {
  if (objArgs == null || typeof objArgs != 'object') {
    return;
  }
  if (!objArgs.url) {
    return;
  }
  if (!objArgs.method) {
    objArgs.method = 'GET';
  }
  if (!objArgs.contentType && objArgs.method == 'POST') {
    objArgs.contentType = 'application/x-www-form-urlencoded';
  }
  if (!objArgs.content) {
    objArgs.content = null;
  }
  if (!objArgs.onLoad) {
    objArgs.onLoad = null;
  }
  if (!objArgs.onError) {
    objArgs.onError = null;
  }
  // Request URL
  var objRequest = Zapatec.Transport.createXmlHttpRequest();
  if (objRequest == null) {
    return;
  }
  /*
    IE 6 calls onreadystatechange and then raises an exception if local file is
    not found. This flag is used to prevent duplicate onError calls.
  */
  var boolErrorDisplayed = false;
  try {
    // Open request
    if (typeof objArgs.username != 'undefined' &&
     typeof objArgs.password != 'undefined') {
      objRequest.open(objArgs.method, objArgs.url, true,
       objArgs.username, objArgs.password);
    } else {
      objRequest.open(objArgs.method, objArgs.url, true);
    }
    // Set onreadystatechange handler
    objRequest.onreadystatechange = function () {
      if (objRequest.readyState == 4) {
        // Request complete
        if (objRequest.status == 200 || objRequest.status == 304 ||
         (location.protocol == 'file:' && objRequest.status == 0)) {
          // OK or found, but determined unchanged and loaded from cache
          if (typeof objArgs.onLoad == 'function') {
            objArgs.onLoad(objRequest);
          }
        } else if (!boolErrorDisplayed) {
          boolErrorDisplayed = true;
          // 404 Not found, etc.
          Zapatec.Transport.displayError(objRequest.status,
           'Error: Cannot fetch ' + objArgs.url + '.\n' +
           (objRequest.statusText || ''),
           objArgs.onError);
        }
      }
    };
    // Set content type if needed
    if (objArgs.contentType) {
      objRequest.setRequestHeader('Content-Type', objArgs.contentType);
    }
    // Send request
    objRequest.send(objArgs.content);
  } catch (objException) {
    if (!boolErrorDisplayed) {
      boolErrorDisplayed = true;
      if (objException.name &&
       objException.name == 'NS_ERROR_FILE_NOT_FOUND') {
        Zapatec.Transport.displayError(0,
         'Error: Cannot fetch ' + objArgs.url + '.\nFile not found.',
         objArgs.onError);
      } else {
        Zapatec.Transport.displayError(0,
         'Error: Cannot fetch ' + objArgs.url + '.\n' +
         (objException.message || ''),
         objArgs.onError);
      }
    }
  };
};

/**
 * Fetches and parses XML document from the specified URL.
 *
 * Asynchronous mode is used because it is safer and there is no risk of having
 * your script hang in case of network problem.
 *
 * When XML document is fetched and parsed, one of provided callback functions
 * is called: onLoad on success or onError on error.
 *
 * onLoad callback function receives XMLDocument object as argument and may use
 * its documentElement and other properties.
 *
 * onError callback function receives following object:
 * {
 *   errorCode: error code [number],
 *   errorDescription: human readable error description [string]
 * }
 * Error code will be 0 unless Zapatec.Transport.fetch was used to fetch URL
 * and there was a problem during fetching.
 *
 * If method argument is not defined, more efficient XMLDOM in IE and
 * document.implementation.createDocument in Mozilla will be used to fetch
 * and parse document. Otherwise Zapatec.Transport.fetch will be used to fetch
 * document and Zapatec.Transport.parseXml to parse.
 *
 * Note: Some browsers implement caching for GET requests. Caching can be
 * prevented by adding 'r=' + Math.random() parameter to URL.
 *
 * If you use POST method, content argument should be something like
 * 'var1=value1&var2=value'. If you wish to send other content, set appropriate
 * contentType. E.g. to send XML string, you should set contentType: 'text/xml'.
 *
 * Server response should not contain non-ASCII characters.
 *
 * \param objArgs [object] associative array with arguments:
 * {
 *   url: relative or absolute URL to fetch [string],
 *   method: method ('GET', 'POST', 'HEAD', 'PUT') [string] (optional),
 *   contentType: content type when using POST [string] (optional),
 *   content: postable string or DOM object data when using POST
 *   [string or object] (optional),
 *   onLoad: function reference to call on success [function],
 *   onError: function reference to call on error [function] (optional),
 *   username: username [string] (optional),
 *   password: password [string] (optional)
 * }
 */
Zapatec.Transport.fetchXmlDoc = function(objArgs) {
  if (objArgs == null || typeof objArgs != 'object') {
    return;
  }
  if (!objArgs.url) {
    return;
  }
  if (!objArgs.onLoad) {
    objArgs.onLoad = null;
  }
  if (!objArgs.onError) {
    objArgs.onError = null;
  }
  if (!objArgs.method && typeof objArgs.username == 'undefined' &&
   typeof objArgs.password == 'undefined') {
    // Try more efficient methods first
    if (document.implementation && document.implementation.createDocument) {
      // Mozilla
      var objDocument = document.implementation.createDocument('', '', null);
      objDocument.async = true;
      objDocument.onload = function() {
        Zapatec.Transport.onXmlDocLoad(objDocument, objArgs.onLoad,
         objArgs.onError);
      };
      try {
        objDocument.load(objArgs.url);
        return;
      } catch (objException) {
        if (objException.name &&
         objException.name == 'NS_ERROR_FILE_NOT_FOUND') {
          Zapatec.Transport.displayError(0,
           'Error: Cannot fetch ' + objArgs.url + '.\nFile not found.',
           objArgs.onError);
        } else {
          Zapatec.Transport.displayError(0,
           'Error: Cannot fetch ' + objArgs.url + '.\n' +
           objException.toString(),
           objArgs.onError);
        }
      };
    }
    if (typeof ActiveXObject != 'undefined') {
      // IE
      try {
        var objDocument = new ActiveXObject(Zapatec.Transport.XMLDOM);
        objDocument.async = true;
        objDocument.onreadystatechange = function () {
          if (objDocument.readyState == 4) {
            Zapatec.Transport.onXmlDocLoad(objDocument, objArgs.onLoad,
             objArgs.onError);
          }
        };
        objDocument.load(objArgs.url);
        return;
      } catch (objException) {};
    }
  }
  // Try XMLHttpRequest
  var objFetchArgs = {
    url: objArgs.url
  };
  if (typeof objArgs.method != 'undefined') {
    objFetchArgs.method = objArgs.method;
  }
  if (typeof objArgs.contentType != 'undefined') {
    objFetchArgs.contentType = objArgs.contentType;
  }
  if (typeof objArgs.content != 'undefined') {
    objFetchArgs.content = objArgs.content;
  }
  objFetchArgs.onLoad = function(objRequest) {
    Zapatec.Transport.parseXml({
      strXml: objRequest.responseText,
      onLoad: objArgs.onLoad,
      onError: objArgs.onError
    });
  };
  objFetchArgs.onError = objArgs.onError;
  if (typeof objArgs.username != 'undefined') {
    objFetchArgs.username = objArgs.username;
  }
  if (typeof objArgs.password != 'undefined') {
    objFetchArgs.password = objArgs.password;
  }
  Zapatec.Transport.fetch(objFetchArgs);
};

/**
 * Parses XML string into XMLDocument object.
 *
 * When XML string is parsed, one of provided callback functions is called:
 * onLoad on success or onError on error.
 * onLoad callback function receives XMLDocument object as argument and may use
 * its documentElement and other properties.
 * onError callback function receives following object:
 * {
 *   errorCode: error code [number],
 *   errorDescription: human readable error description [string]
 * }
 * Error code will be always 0.
 *
 * Returns XMLDocument object, so onLoad callback function is optional.
 * Returned value and its documentElement property should be checked before
 * use because they can be null or undefined.
 *
 * \param objArgs [object] associative array with arguments:
 * {
 *   strXml: XML string to parse [string],
 *   onLoad: function reference to call on success [function] (optional),
 *   onError: function reference to call on error [function] (optional)
 * }
 * \return [object] XMLDocument object.
 */
Zapatec.Transport.parseXml = function(objArgs) {
  if (objArgs == null || typeof objArgs != 'object') {
    return;
  }
  if (!objArgs.strXml) {
    return;
  }
  if (!objArgs.onLoad) {
    objArgs.onLoad = null;
  }
  if (!objArgs.onError) {
    objArgs.onError = null;
  }
  if (window.DOMParser) {
    // Mozilla
    try {
      var objDocument = (new DOMParser()).parseFromString(objArgs.strXml,
       'text/xml');
      Zapatec.Transport.onXmlDocLoad(objDocument, objArgs.onLoad,
       objArgs.onError);
      return objDocument;
    } catch (objException) {
      Zapatec.Transport.displayError(0,
       'Error: Cannot parse.\n' +
       'String does not appear to be a valid XML fragment.',
       objArgs.onError);
    };
    return null;
  }
  if (typeof ActiveXObject != 'undefined') {
    // IE
    try {
      var objDocument = new ActiveXObject(Zapatec.Transport.XMLDOM);
      objDocument.loadXML(objArgs.strXml);
      Zapatec.Transport.onXmlDocLoad(objDocument, objArgs.onLoad,
       objArgs.onError);
      return objDocument;
    } catch (objException) {};
  }
};

/**
 * \internal Checks if there were errors during XML document fetching and
 * parsing and calls onLoad or onError callback function correspondingly.
 *
 * \param objDocument [object] XMLDocument object.
 * \param onLoad [function] callback function provided by user.
 * \param onError [function] callback function provided by user.
 */
Zapatec.Transport.onXmlDocLoad = function(objDocument, onLoad, onError) {
  var strError = null;
  if (objDocument.parseError) {
    // Parsing error in IE
    strError = objDocument.parseError.reason;
    if (objDocument.parseError.srcText) {
      strError += 'Location: ' + objDocument.parseError.url +
       '\nLine number ' + objDocument.parseError.line + ', column ' +
       objDocument.parseError.linepos + ':\n' +
       objDocument.parseError.srcText + '\n';
    }
  } else if (objDocument.documentElement &&
   objDocument.documentElement.tagName == 'parsererror') {
    // If an error is caused while parsing, Mozilla doesn't throw an exception.
    // Instead, it creates an XML string containing the details of the error:
    // <parsererror xmlns="http://www.w3.org/1999/xhtml">XML Parsing Error: ...
    // Check if strings has been generated.
    strError = objDocument.documentElement.firstChild.data + '\n' +
     objDocument.documentElement.firstChild.nextSibling.firstChild.data;
  } else if (!objDocument.documentElement) {
    strError = 'String does not appear to be a valid XML fragment.';
  }
  if (strError) {
    // Parsing error
    Zapatec.Transport.displayError(0,
     'Error: Cannot parse.\n' + strError,
     onError);
  } else {
    // Success
    if (typeof onLoad == 'function') {
      onLoad(objDocument);
    }
  }
};

/**
 * Serializes XMLDocument object into XML string.
 *
 * \param objDocument [object] XMLDocument object.
 * \return [string] XML string.
 */
Zapatec.Transport.serializeXmlDoc = function(objDocument) {
  if (window.XMLSerializer) {
    // Mozilla
    return (new XMLSerializer).serializeToString(objDocument);
  }
  if (objDocument.xml) {
    // IE
    return objDocument.xml;
  }
};

/**
 * Fetches and parses JSON object from the specified URL.
 *
 * Asynchronous mode is used because it is safer and there is no risk of having
 * your script hang in case of network problem.
 *
 * When JSON object is fetched and parsed, one of provided callback functions
 * is called: onLoad on success or onError on error.
 *
 * onLoad callback function receives JSON object as argument.
 *
 * onError callback function receives following object:
 * {
 *   errorCode: error code [number],
 *   errorDescription: human readable error description [string]
 * }
 * Error code will be 0 unless there was a problem during fetching.
 *
 * Note: Some browsers implement caching for GET requests. Caching can be
 * prevented by adding 'r=' + Math.random() parameter to URL.
 *
 * If you use POST method, content argument should be something like
 * 'var1=value1&var2=value'. If you wish to send other content, set appropriate
 * contentType. E.g. to send XML string, you should set contentType: 'text/xml'.
 *
 * Server response should not contain non-ASCII characters.
 *
 * \param objArgs [object] associative array with arguments:
 * {
 *   url: relative or absolute URL to fetch [string],
 *   reliable: false (string will be parsed) or true (evaluated) [boolean]
 *   (optional, false by default),
 *   method: method ('GET', 'POST', 'HEAD', 'PUT') [string] (optional),
 *   contentType: content type when using POST [string] (optional),
 *   content: postable string or DOM object data when using POST
 *   [string or object] (optional),
 *   onLoad: function reference to call on success [function],
 *   onError: function reference to call on error [function] (optional),
 *   username: username [string] (optional),
 *   password: password [string] (optional)
 * }
 */
Zapatec.Transport.fetchJsonObj = function(objArgs) {
  if (objArgs == null || typeof objArgs != 'object') {
    return;
  }
  if (!objArgs.url) {
    return;
  }
  var objFetchArgs = {
    url: objArgs.url
  };
  if (typeof objArgs.method != 'undefined') {
    objFetchArgs.method = objArgs.method;
  }
  if (typeof objArgs.contentType != 'undefined') {
    objFetchArgs.contentType = objArgs.contentType;
  }
  if (typeof objArgs.content != 'undefined') {
    objFetchArgs.content = objArgs.content;
  }
  if (!objArgs.reliable) {
    objArgs.reliable = false;
  }
  if (!objArgs.onLoad) {
    objArgs.onLoad = null;
  }
  if (!objArgs.onError) {
    objArgs.onError = null;
  }
  objFetchArgs.onLoad = function(objRequest) {
    Zapatec.Transport.parseJson({
      strJson: objRequest.responseText,
      reliable: objArgs.reliable,
      onLoad: objArgs.onLoad,
      onError: objArgs.onError
    });
  };
  objFetchArgs.onError = objArgs.onError;
  if (typeof objArgs.username != 'undefined') {
    objFetchArgs.username = objArgs.username;
  }
  if (typeof objArgs.password != 'undefined') {
    objFetchArgs.password = objArgs.password;
  }
  Zapatec.Transport.fetch(objFetchArgs);
};

/**
 * Parses JSON string into object.
 *
 * When JSON string is parsed, one of provided callback functions is called:
 * onLoad on success or onError on error.
 *
 * onLoad callback function receives JSON object as argument.
 *
 * onError callback function receives following object:
 * {
 *   errorCode: error code [number],
 *   errorDescription: human readable error description [string]
 * }
 * Error code will be always 0.
 *
 * Returns JSON object, so onLoad callback function is optional.
 * Returned value should be checked before use because it can be null.
 *
 * \param objArgs [object] associative array with arguments:
 * {
 *   strJson: XML string to parse [string],
 *   reliable: false (string will be parsed) or true (evaluated) [boolean]
 *   (optional, false by default),
 *   onLoad: function reference to call on success [function] (optional),
 *   onError: function reference to call on error [function] (optional)
 * }
 * \return [object] JSON object or null.
 */
Zapatec.Transport.parseJson = function(objArgs) {
  if (objArgs == null || typeof objArgs != 'object') {
    return;
  }
  if (!objArgs.strJson) {
    return;
  }
  if (!objArgs.reliable) {
    objArgs.reliable = false;
  }
  if (!objArgs.onLoad) {
    objArgs.onLoad = null;
  }
  if (!objArgs.onError) {
    objArgs.onError = null;
  }
  if (objArgs.reliable) {
    try {
      var objJson = eval('(' + objArgs.strJson + ')');
      if (typeof objArgs.onLoad == 'function') {
        objArgs.onLoad(objJson);
      }
      return objJson;
    } catch (objException) {
      Zapatec.Transport.displayError(0,
       'Error: Cannot parse.\n' +
       'String does not appear to be a valid JSON fragment.',
       objArgs.onError);
    };
  } else {
    try {
      var objJson = Zapatec.Transport.parseJsonStr(objArgs.strJson);
      if (typeof objArgs.onLoad == 'function') {
        objArgs.onLoad(objJson);
      }
      return objJson;
    } catch (objException) {
      Zapatec.Transport.displayError(0,
       'Error: Cannot parse.\n' +
       objException.message + '\n' + objException.text,
       objArgs.onError);
    };
  }
  return null;
};

/**
 * \internal Parses JSON string into object.
 *
 * Was taken with changes from http://www.crockford.com/JSON/json.js.
 *
 * Throws exception if parsing error occurs.
 *
 * JSON format is described at http://www.crockford.com/JSON/js.html.
 *
 * \param text [string] JSON string to parse.
 * \return [object] JSON object.
 */
Zapatec.Transport.parseJsonStr = function(text) {
  var p = /^\s*(([,:{}\[\]])|"(\\.|[^\x00-\x1f"\\])*"|-?\d+(\.\d*)?([eE][+-]?\d+)?|true|false|null)\s*/,
      token,
      operator;
  function error(m, t) {
      throw {
          name: 'JSONError',
          message: m,
          text: t || operator || token
      };
  }
  function next(b) {
      if (b && b != operator) {
          error("Expected '" + b + "'");
      }
      if (text) {
          var t = p.exec(text);
          if (t) {
              if (t[2]) {
                  token = null;
                  operator = t[2];
              } else {
                  operator = null;
                  try {
                      token = eval(t[1]);
                  } catch (e) {
                      error("Bad token", t[1]);
                  }
              }
              text = text.substring(t[0].length);
          } else {
              error("Unrecognized token", text);
          }
      } else {
          // undefined changed to null because it is not supported in IE 5.0
          token = operator = null;
      }
  }
  function val() {
      var k, o;
      switch (operator) {
      case '{':
          next('{');
          o = {};
          if (operator != '}') {
              for (;;) {
                  if (operator || typeof token != 'string') {
                      error("Missing key");
                  }
                  k = token;
                  next();
                  next(':');
                  o[k] = val();
                  if (operator != ',') {
                      break;
                  }
                  next(',');
              }
          }
          next('}');
          return o;
      case '[':
          next('[');
          o = [];
          if (operator != ']') {
              for (;;) {
                  o.push(val());
                  if (operator != ',') {
                      break;
                  }
                  next(',');
              }
          }
          next(']');
          return o;
      default:
          if (operator !== null) {
              error("Missing value");
          }
          k = token;
          next();
          return k;
      }
  }
  next();
  return val();
};

/**
 * Serializes JSON object into JSON string.
 *
 * Was taken with changes from http://www.crockford.com/JSON/json.js.
 *
 * \param v [object] JSON object.
 * \return [string] JSON string.
 */
Zapatec.Transport.serializeJsonObj = function(v) {
  var a = [];
  /*
    Emit a string.
  */
  function e(s) {
      a[a.length] = s;
  }
  /*
    Convert a value.
  */
  function g(x) {
      var c, i, l, v;
      switch (typeof x) {
      case 'object':
          if (x) {
              if (x instanceof Array) {
                  e('[');
                  l = a.length;
                  for (i = 0; i < x.length; i += 1) {
                      v = x[i];
                      if (typeof v != 'undefined' &&
                              typeof v != 'function') {
                          if (l < a.length) {
                              e(',');
                          }
                          g(v);
                      }
                  }
                  e(']');
                  return;
              } else if (typeof x.toString != 'undefined') {
                  e('{');
                  l = a.length;
                  for (i in x) {
                      v = x[i];
                      if (x.hasOwnProperty(i) &&
                              typeof v != 'undefined' &&
                              typeof v != 'function') {
                          if (l < a.length) {
                              e(',');
                          }
                          g(i);
                          e(':');
                          g(v);
                      }
                  }
                  return e('}');
              }
          }
          e('null');
          return;
      case 'number':
          e(isFinite(x) ? +x : 'null');
          return;
      case 'string':
          l = x.length;
          e('"');
          for (i = 0; i < l; i += 1) {
              c = x.charAt(i);
              if (c >= ' ') {
                  if (c == '\\' || c == '"') {
                      e('\\');
                  }
                  e(c);
              } else {
                  switch (c) {
                      case '\b':
                          e('\\b');
                          break;
                      case '\f':
                          e('\\f');
                          break;
                      case '\n':
                          e('\\n');
                          break;
                      case '\r':
                          e('\\r');
                          break;
                      case '\t':
                          e('\\t');
                          break;
                      default:
                          c = c.charCodeAt();
                          e('\\u00' + Math.floor(c / 16).toString(16) +
                              (c % 16).toString(16));
                  }
              }
          }
          e('"');
          return;
      case 'boolean':
          e(String(x));
          return;
      default:
          e('null');
          return;
      }
  }
  g(v);
  return a.join('');
};

/**
 * \internal Displays error message.
 *
 * Calls onError callback function provided by user. If there is no onError
 * callback function, displays alert with human readable error description.
 * onError callback function receives following object:
 * {
 *   errorCode: error code [number],
 *   errorDescription: human readable error description [string]
 * }
 *
 * \param iErrCode [number] error code.
 * \param strError [string] human readable error description.
 * \param onError [function] callback function provided by user.
 */
Zapatec.Transport.displayError = function(iErrCode, strError, onError) {
  if (typeof onError == 'function') {
    onError({
      errorCode: iErrCode,
      errorDescription: strError
    });
  } else {
    alert(strError);
  }
};


/*
  to do
  1) add function that transforms relative url to absolute
  2) add preload of background images in css
*/

/**
 * \internal Associative array to keep list of loaded JS files to prevent
 * duplicate loads.
 */
Zapatec.Transport.loadedJS = {};

/**
 * String variable containing path where dynamically loaded JS modules should be
 * searched.
 *
 * It is recommended to set this variable before calling loadJS with module
 * argument.
 */
Zapatec.Transport.JSModulesPath = null;

/**
 * Fetches JS file using fetch and evaluates it in global scope.
 *
 * When JS file is loaded successfully, onLoad callback function is called
 * without arguments. URL is added into Zapatec.Transport.loadedJS array
 * and will not be fetched again on next function call.
 *
 * onError callback function receives following object:
 * {
 *   errorCode: server status number (404, etc.) [number],
 *   errorDescription: human readable error description [string]
 * }
 *
 * One of the arguments: module or url is required. When url is passed,
 * module argument is ignored.
 *
 * Path to the module can be set manually in Zapatec.Transport.JSModulesPath
 * global variable before this function call. If not set manually it is
 * determined automatically:
 * On the first call with module argument, function gets all "script" elements
 * using getElementsByTagName and searches for the first element having "src"
 * attribute value ending with "transport.js". Path to the module is taken from
 * that src attribute value and will be the same as path to transport.js file.
 * Path to the module is saved to Zapatec.Transport.JSModulesPath variable. Once
 * it is set, function will not attempt to determine it again on the future
 * calls.
 *
 * \param objArgs [object] associative array with arguments:
 * {
 *   url: absolute or relative URL of JS file [string] (optional),
 *   module: module name (file name without .js extension) [string] (optional),
 *   onLoad: function reference to call on success [function] (optional),
 *   onError: function reference to call on error [function] (optional)
 * }
 */
Zapatec.Transport.loadJS = function(objArgs) {
  if (objArgs == null || typeof objArgs != 'object') {
    return;
  }
  var strUrl = null;
  if (objArgs.url) {
    strUrl = objArgs.url;
  } else if (objArgs.module) {
    if (Zapatec.Transport.JSModulesPath == null) {
      // Get path to module
      var arrScripts = document.getElementsByTagName('script');
      for (var iScript = 0; iScript < arrScripts.length; iScript++) {
        var strSrc = arrScripts[iScript].getAttribute('src') || '';
        var arrTokens = strSrc.split('/');
        // Remove last token
        var strLastToken = arrTokens.pop();
        if (strLastToken == 'transport.js') {
          Zapatec.Transport.JSModulesPath = arrTokens.join('/') + '/';
          break;
        }
      }
      if (Zapatec.Transport.JSModulesPath == null) {
        Zapatec.Transport.JSModulesPath = '';
      }
    }
    strUrl = Zapatec.Transport.JSModulesPath + objArgs.module + '.js';
  } else {
    return;
  }
  if (!objArgs.onLoad) {
    objArgs.onLoad = null;
  }
  if (!objArgs.onError) {
    objArgs.onError = null;
  }
  // Check if it is already loaded
  if (Zapatec.Transport.loadedJS[strUrl]) {
    if (typeof objArgs.onLoad == 'function') {
      objArgs.onLoad();
    }
    return;
  }
  var arrScripts = document.getElementsByTagName('script');
  for (var iScript = 0; iScript < arrScripts.length; iScript++) {
    var strSrc = arrScripts[iScript].getAttribute('src') || '';
    if (strSrc == strUrl) {
      Zapatec.Transport.loadedJS[strUrl] = true;
      if (typeof objArgs.onLoad == 'function') {
        objArgs.onLoad();
      }
      return;
    }
  }
  // Load JS file
  Zapatec.Transport.fetch({
    url: strUrl,
    onLoad: function(objRequest) {
      setTimeout(function() {
        // Evaluate code in global scope
        eval(objRequest.responseText);
        if (typeof objArgs.onLoad == 'function') {
          objArgs.onLoad();
        }
      }, 0);
    },
    onError: objArgs.onError
  });
};

/**
 * \internal Associative array to keep list of loaded CSS files to prevent
 * duplicate loads.
 */
Zapatec.Transport.loadedCss = {};

/**
 * Fetches style sheet using fetch and loads it into the document.
 *
 * When stylesheet is loaded successfully, onLoad callback function is called
 * without arguments. URL is added into Zapatec.Transport.loadedCss array
 * and will not be fetched again on next function call.
 *
 * onError callback function receives following object:
 * {
 *   errorCode: server status number (404, etc.) [number],
 *   errorDescription: human readable error description [string]
 * }
 *
 * \param objArgs [object] associative array with arguments:
 * {
 *   url: absolute or relative URL of CSS file [string],
 *   onLoad: function reference to call on success [function] (optional),
 *   onError: function reference to call on error [function] (optional)
 * }
 */
Zapatec.Transport.loadCss = function(objArgs) {
  if (objArgs == null || typeof objArgs != 'object') {
    return;
  }
  if (!objArgs.url) {
    return;
  }
  if (!objArgs.onLoad) {
    objArgs.onLoad = null;
  }
  if (!objArgs.onError) {
    objArgs.onError = null;
  }
  // Check if it is already loaded
  if (Zapatec.Transport.loadedCss[objArgs.url]) {
    if (typeof objArgs.onLoad == 'function') {
      objArgs.onLoad();
    }
    return;
  }
  var arrLinks = document.getElementsByTagName('link');
  for (var iLnk = 0; iLnk < arrLinks.length; iLnk++) {
    var strHref = arrLinks[iLnk].getAttribute('href') || '';
    if (strHref == objArgs.url) {
      Zapatec.Transport.loadedCss[objArgs.url] = true;
      if (typeof objArgs.onLoad == 'function') {
        objArgs.onLoad();
      }
      return;
    }
  }
  // Load Zapatec.StyleSheet class definition
  Zapatec.Transport.loadJS({
    module: 'stylesheet',
    onLoad: function() {
      // Load CSS file
      Zapatec.Transport.fetch({
        url: objArgs.url,
        onLoad: function(objRequest) {
          (new Zapatec.StyleSheet()).addParse(objRequest.responseText);
          Zapatec.Transport.loadedCss[objArgs.url] = true;
          if (typeof objArgs.onLoad == 'function') {
            objArgs.onLoad();
          }
        },
        onError: objArgs.onError
      });
    },
    onError: objArgs.onError
  });
};
