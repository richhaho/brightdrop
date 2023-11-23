window.inputFormatter =
{
	init: function (selector)
	{
		document.querySelectorAll(selector).forEach(elem => this.initNumericField(elem));
	},

	extractNumber: function (str)
	{
		let s = '';

		for (let i = 0; i < str.length; i++)
			if (str[i].match(/[0-9\.]/)) s += str[i];

		return s;
	},

	formatNumber: function (str, decimals=null)
	{
		let value = this.extractNumber(str);
		if (!value) return '';

		let s = '';

		let i = 0;
		while (i < value.length && value[i].match(/[0-9]/))
			s += value[i++];

		for (let j = s.length-3; j > 0; j -= 3)
			s = s.substr(0, j) + ',' + s.substr(j);

		if (value[i] == '.')
		{
			let fract = '.';
			i++;

			while (i < value.length && value[i].match(/[0-9]/))
				fract += value[i++];

			if (fract != '.')
			{
				if (decimals === null)
					s += fract;//s += '.' + parseFloat(fract).toString().substr(2);
				else
					s += '.' + parseFloat(fract).toFixed(decimals).substr(2);
			}
			else
				s += '.';
		}

		return s;
	},

	initNumericField: function (elem)
	{
		let _elem = elem.cloneNode(true);
		_elem.style.display = 'none';

		elem.parentElement.insertBefore(_elem, elem.nextElementSibling);

		elem.removeAttribute('name');
		elem.removeAttribute('id');

		elem.onkeydown = (evt) =>
		{
			if (evt.key.match(/Backspace|Enter|Tab|Left|Right|Home|End|Del/))
				return;

			if (!evt.key.match(/[0-9\.]/))
			{
				evt.preventDefault();
				return;
			}

			if (evt.key == '.' && evt.target.value.indexOf('.') !== -1)
			{
				evt.preventDefault();
				return;
			}
		};

		elem.onkeyup = (evt) =>
		{
			let s = elem.value;
			let p = this.formatNumber(s);

			if (s != p)
			{
				elem.value = p;
				evt.preventDefault();
			}
		};

		elem.onchange = (evt) =>
		{
			elem.value = this.formatNumber(elem.value, 2);

			_elem.value = this.extractNumber(elem.value);
			_elem.dispatchEvent (new CustomEvent ('change', { bubbles: true, detail: null }));
		};

		_elem.onchange = (evt) =>
		{
			let value = this.formatNumber(this.extractNumber(_elem.value), 2);
			if (value !== elem.value)
			{
				elem.value = value;
			}
		};
	}
};