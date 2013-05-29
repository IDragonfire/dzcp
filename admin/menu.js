/*
  © 2003 by www.softcomplex.com
  
  modified by CodeKing for DZCP 11-14-2006 (mm-dd-yyyy)
*/

var POS = [{
	'height': 18,
	'width': 110,
	'block_top': 0,
	'block_left': 0,
	'top': 0,
	'left': 111,
	'hide_delay': 500,
	'expd_delay': 0,
	'css' : {
		'outer' : ['adminBarOuter', 'adminBarOuterOver'],
		'inner' : ['adminBarInner', 'adminBarInnerOver']
	}
},{
	'height': 21,
	'width': 221,
	'block_top': 19,
	'block_left': 0,
	'top': 21,
	'left': 0,
	'css' : {
		'outer' : ['adminMenuOuter', 'adminMenuOuterOver'],
		'inner' : ['adminMenuInner', 'adminMenuInnerOver']
	}
},{
	'block_top': 0,
	'block_left': 171
}
]

var A_MENUS = [];
var isEditor = false;

function checkEditor()
{
  var tag=document.getElementsByTagName("textarea");
  for(var i=0;i<tag.length;i++)
  {
    if(tag[i].className == "editorStyleWord" || tag[i].className == "editorStyleMini" || tag[i].className == "editorStyleNewsletter")
      isEditor = true;
  }
}
DZCP.addEvent(window, 'load', checkEditor);
  
function menu (a_items, a_tpl) 
{
	if (!doc.body || !doc.body.style)
		return;
	this.a_config = a_items;
	this.a_tpl = a_tpl;
	this.n_id = A_MENUS.length;
	this.a_index = [];
	this.a_children = [];
	this.expand      = menu_expand;
	this.collapse    = menu_collapse;
	this.onclick     = menu_onclick;
	this.onmouseout  = menu_onmouseout;
	this.onmouseover = menu_onmouseover;
	this.onmousedown = menu_onmousedown;
	this.o_root = this;
	this.n_depth = -1;
	this.n_x = 0;
	this.n_y = 0;
	for (n_order = 0; n_order < a_items.length; n_order++)
		new menu_item(this, n_order);

	A_MENUS[this.n_id] = this;

	for (var n_order = 0; n_order < this.a_children.length; n_order++) {
		this.a_children[n_order].e_oelement.style.visibility = 'visible';
  }
}

function menu_collapse (n_id) 
{
	clearTimeout(this.o_showtimer);
	var n_tolevel = (n_id ? this.a_index[n_id].n_depth : 0);
	
	for (n_id = 0; n_id < this.a_index.length; n_id++) {
		var o_curritem = this.a_index[n_id];
		if (o_curritem.n_depth > n_tolevel && o_curritem.b_visible) {
      if(ie4 && !opera && isEditor) $('#admContent').css('display', '');
			o_curritem.e_oelement.style.visibility = 'hidden';
			o_curritem.b_visible = false;   
		}
	}
  
	if (!n_id) this.o_current = null;
}

function menu_expand (n_id) 
{
	if (this.o_hidetimer)
		return;

	var o_item = this.a_index[n_id];

	if (this.o_current && this.o_current.n_depth >= o_item.n_depth)
		this.collapse(o_item.n_id);
	this.o_current = o_item;

	if (!o_item.a_children)
		return;

	for (var n_order = 0; n_order < o_item.a_children.length; n_order++) {
		var o_curritem = o_item.a_children[n_order];
    if(ie4 && !opera && isEditor) $('#admContent').css('display', 'none'); 
		o_curritem.e_oelement.style.visibility = 'visible';
		o_curritem.b_visible = true;
	}
}

function menu_onclick (n_id) 
{
	return Boolean(this.a_index[n_id].a_config[1]);
}

function menu_onmouseout (n_id) 
{
	var o_item = this.a_index[n_id];

	o_item.e_oelement.className = o_item.getstyle(0, 0);
	o_item.e_ielement.className = o_item.getstyle(1, 0);
	o_item.upstatus(7);

	this.o_hidetimer = setTimeout('A_MENUS['+ this.n_id +'].collapse();',
		o_item.getprop('hide_delay'));
}

function menu_onmouseover (n_id) 
{
	clearTimeout(this.o_hidetimer);
	this.o_hidetimer = null;
	clearTimeout(this.o_showtimer);

	var o_item = this.a_index[n_id];
  
	o_item.upstatus();
	o_item.e_oelement.className = o_item.getstyle(0, 1);
	o_item.e_ielement.className = o_item.getstyle(1, 1);
  
	if (o_item.getprop('expd_delay') < 0)
		return;
    
	this.o_showtimer = setTimeout('A_MENUS['+ this.n_id +'].expand(' + n_id + ');',
		o_item.getprop('expd_delay'));
}

function menu_onmousedown (n_id) 
{	
	var o_item = this.a_index[n_id];

	o_item.e_oelement.className = o_item.getstyle(0, 2);
	o_item.e_ielement.className = o_item.getstyle(1, 2);

	this.expand(n_id);
}

function menu_item (o_parent, n_order) 
{
	this.n_depth  = o_parent.n_depth + 1;
	this.a_config = o_parent.a_config[n_order + (this.n_depth ? 3 : 0)];

	if (!this.a_config) return;

	this.o_root    = o_parent.o_root;
	this.o_parent  = o_parent;
	this.n_order   = n_order;
	this.n_id = this.o_root.a_index.length;
	this.o_root.a_index[this.n_id] = this;
	o_parent.a_children[n_order] = this;

	var o_root = this.o_root,
		a_tpl  = this.o_root.a_tpl;

	this.getprop  = mitem_getprop;
	this.getstyle = mitem_getstyle;
	this.upstatus = mitem_upstatus;

	this.n_x = n_order
		? o_parent.a_children[n_order - 1].n_x + this.getprop('left')
		: o_parent.n_x + this.getprop('block_left');

	this.n_y = n_order
		? o_parent.a_children[n_order - 1].n_y + this.getprop('top')
		: o_parent.n_y + this.getprop('block_top');
     
	doc.write (
		'<a id="e' + o_root.n_id + '_'
			+ this.n_id +'o" class="' + this.getstyle(0, 0) + '" href="' + this.a_config[1] + '"'
			+ (this.a_config[2] && this.a_config[2]['tw'] ? ' target="'
			+ this.a_config[2]['tw'] + '"' : '')
			+ ' title="' + this.a_config[0] + '" style="position: absolute; top: '
			+ this.n_y + 'px; left: ' + this.n_x + 'px; width: '
			+ this.getprop('width') + 'px; height: '
			+ this.getprop('height') + 'px; visibility: hidden;'
			+ this.a_config[2] + ' z-index: ' + this.n_depth + ';" '
			+ 'onclick="return A_MENUS[' + o_root.n_id + '].onclick('
			+ this.n_id + ');" onmouseout="A_MENUS[' + o_root.n_id + '].onmouseout('
			+ this.n_id + ');" onmouseover="A_MENUS[' + o_root.n_id + '].onmouseover('
			+ this.n_id + ');" onmousedown="A_MENUS[' + o_root.n_id + '].onmousedown('
			+ this.n_id + ');"><div  id="e' + o_root.n_id + '_'
			+ this.n_id +'i" class="' + this.getstyle(1, 0) + '">'
			+ this.a_config[0] + "</div></a>\n"
		);
	this.e_ielement = $('#e' + o_root.n_id + '_' + this.n_id + 'i')[0];
	this.e_oelement = $('#e' + o_root.n_id + '_' + this.n_id + 'o')[0];

	this.b_visible = !this.n_depth;

	if (this.a_config.length < 4)
		return;

	this.a_children = [];

	for (var n_order = 0; n_order < this.a_config.length - 3; n_order++)
		new menu_item(this, n_order);
}

function mitem_getprop (s_key) 
{
	var s_value = null,
		a_level = this.o_root.a_tpl[this.n_depth];

	if (a_level)
		s_value = a_level[s_key];

	return (s_value == null ? this.o_parent.getprop(s_key) : s_value);
}

function mitem_getstyle (n_pos, n_state) 
{
	var a_css = this.getprop('css');
	var a_oclass = a_css[n_pos ? 'inner' : 'outer'];

	if (typeof(a_oclass) == 'string')
		return a_oclass;

	for (var n_currst = n_state; n_currst >= 0; n_currst--)
		if (a_oclass[n_currst])
			return a_oclass[n_currst];
}

function mitem_upstatus (b_clear) 
{
	window.setTimeout("window.status=unescape('" + (b_clear
		? ''
		: (this.a_config[2] && this.a_config[2]['sb']
			? escape(this.a_config[2]['sb'])
			: escape(this.a_config[0]) + (this.a_config[1]
				? ' ('+ escape(this.a_config[1]) + ')'
				: ''))) + "')", 10);
}