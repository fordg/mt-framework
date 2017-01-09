{
  type   : 'container',
  label  : 'HTML',
  html   : '<b>container</b> can contains html directly, or use layout.'
},
{
  type   : 'listbox',
  name   : 'listbox',
  label  : 'listbox',
  values : [
    { text: 'Test', value: 'test' },
    { text: 'Test2', value: 'test2', selected: true }
  ]
},
{
  type   : 'combobox',
  name   : 'combobox',
  label  : 'combobox',
  values : [
    { text: 'Test', value: 'test' },
    { text: 'Test2', value: 'test2' }
  ]
},
{
  type   : 'textbox',
  name   : 'textbox',
  label  : 'textbox',
  tooltip: 'Some nice tooltip to use',
  value  : 'default value'
},
{
  type   : 'textbox',
  name   : 'textbox multiline',
  label  : 'textbox multiline',
  multiline : true,
  value  : 'default value\non another line'
},
{
  type   : 'checkbox',
  name   : 'checkbox',
  label  : 'checkbox',
  text   : 'My Checkbox',
  checked : true
},
{
  type   : 'colorbox',
  name   : 'colorbox',
  label  : 'colorbox',
  onaction: pick_color,
},
{
  type   : 'colorpicker',
  name   : 'colorpicker',
  label  : 'colorpicker'
},
{
  type   : 'radio',
  name   : 'radio',
  label  : 'radio ( checkbox a class of "radio" )',
  text   : 'My Radio Button'
}
