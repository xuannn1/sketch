var hasLocalStorage = function () {
	var mod = 'a';
	try {
		localStorage.setItem(mod, mod);
		localStorage.removeItem(mod);
		return true;
	} catch (e) {
		return false;
	}
};

var changeLogo = function (theme) {
  if (theme == "dark") {
    document.getElementById('logo').children[0].src='/img/sosad-logo-dark.png';
  }
  else {
    document.getElementById('logo').children[0].src='/img/sosad-logo.png';
  }
};

function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}

var themeName = localStorage.getItem("theme") || getCookie("theme") || 'default';
var root = document.querySelector(':root');
root.className = themeName;

window.onload = function () {
  var themeChanger = document.querySelector('#theme-changer'),
        themeInput = document.querySelector('#theme'),
        selectOption;
  if (hasLocalStorage() && window.CSS && window.CSS.supports && window.CSS.supports('--a', 0)) {
    themeInput.addEventListener('change', function (e) {
			selectOption = this.options[this.selectedIndex];
			themeName = selectOption.value;
			root.className = themeName;
			localStorage.setItem('theme', themeName);
      changeLogo(themeName);
		});
  }
  else {
    themeInput.addEventListener('change', function (e) {
			selectOption = this.options[this.selectedIndex];
			themeName = selectOption.value;
			root.className = themeName;
      document.cookie = "theme="+themeName+";";
      changeLogo(themeName);
		});
  }

  themeInput.value = themeName;
  changeLogo(themeName);
};
