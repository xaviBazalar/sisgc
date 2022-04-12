/*global 
sessvars

*/
var inconcert = inconcert || {};

/**
El storage se usa así:
Incluir en la página este script: "/inConcert/lib/sessvars/sessvars.js"

Luego, hay que llamar a inconcert.storage.Setup() antes de pode usarlo y esto hay que hacerlo en el evento onready o similar

- inconcert.storage.Setup hay que llamarla en el onready de la pagina
- inconcert.storage.Reset borra todo el storage
- inconcert.storage.Put(clave, valor) guarda un elemento
- inconcert.storage.Get(clave) obtiene un elemento
- inconcert.storage.Erase(clave) borra un elemento

*/

inconcert.storage = {
	Setup: function() {
		sessvars.$.prefs.autoFlush = true;
		//sessvars.$.debug();
	},
	
	Reset: function() {
		sessvars.$.clearMem();
	},
	
	Put: function(key, value) {
		sessvars[key] = value;
	},
	
	Get: function(key) {
		return sessvars[key];
	},
	
	Erase: function(key) {
		delete sessvars[key];
	}
};