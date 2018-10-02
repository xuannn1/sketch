// test for localStorage support
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

var themeName = localStorage.getItem("theme") || 'default';
var root = document.querySelector(':root');
root.className = themeName;

window.onload = function () {
  if (hasLocalStorage() && window.CSS && window.CSS.supports && window.CSS.supports('--a', 0)) {
    // get values of custom properties and apply
    var themeChanger = document.querySelector('#theme-changer'),
          themeInput = document.querySelector('#theme'),
          selectOption;
    themeInput.addEventListener('change', function (e) {
			selectOption = this.options[this.selectedIndex];
			themeName = selectOption.value;
			root.className = themeName;
			localStorage.setItem('theme', themeName);
      if (themeName == "dark") {
        document.getElementById('logo').children[0].src='/img/sosad-logo-dark.png';
      }
      else {
        document.getElementById('logo').children[0].src='/img/sosad-logo.png';
      }
		});


    themeInput.value = themeName;
    if (themeName == "dark") {
      document.getElementById('logo').children[0].src='/img/sosad-logo-dark.png';
    }
    else {
      document.getElementById('logo').children[0].src='/img/sosad-logo.png';
    }
  }
  else {
  		// CSS custom properties not supported - don't show the theme changer
  		alert("浏览器不支持换肤功能 (つд`) 只能使用默认主题");
  }
};
