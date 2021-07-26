<?php
/*
** Zabbix
** Copyright (C) 2001-2021 Zabbix SIA
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
**/


/**
 * @var CView $this
 */

$scripts = [
	'class.calendar.js', 'class.cverticalaccordion.js', 'class.cviewswitcher.js', 'class.tabfilter.js',
	'class.tabfilteritem.js', 'class.tagfilteritem.js', 'class.tab-indicators.js', 'gtlc.js', 'hostinterfacemanager.js',
	'hostmacrosmanager.js', 'hostpopup.js', 'inputsecret.js', 'macrovalue.js',  'menupopup.js', 'multiselect.js',
	'layout.mode.js', 'textareaflexible.js'
];

foreach($scripts as $script) {
	$this->addJsFile($script);
}

$this->enableLayoutModes();
$web_layout_mode = $this->getLayoutMode();

$widget = (new CWidget())
	->setTitle(_('Hosts'))
	->setWebLayoutMode($web_layout_mode)
	->setControls(
		(new CTag('nav', true, (new CList())
			->addItem(
				(new CSimpleButton(_('Create host')))
					->addClass('js-create-host')
			)
			->addItem(get_icon('kioskmode', ['mode' => $web_layout_mode]))
		))
			->setAttribute('aria-label', _('Content controls'))
	);

if ($web_layout_mode == ZBX_LAYOUT_NORMAL) {
	$filter = (new CTabFilter())
		->setId('monitoring_hosts_filter')
		->setOptions($data['tabfilter_options'])
		->addTemplate(new CPartial($data['filter_view'], $data['filter_defaults']));

	foreach ($data['filter_tabs'] as $tab) {
		$tab['tab_view'] = $data['filter_view'];
		$filter->addTemplatedTab($tab['filter_name'], $tab);
	}

	// Set javascript options for tab filter initialization in monitoring.host.view.js.php file.
	$data['filter_options'] = $filter->options;
	$widget->addItem($filter);
}
else {
	$data['filter_options'] = null;
}

$widget->addItem((new CForm())->setName('host_view')->addClass('is-loading'));
$widget->show();
$this->includeJsFile('monitoring.host.view.js.php', $data);

(new CScriptTag('host_page.start();host_popup.init();'))
	->setOnDocumentReady()
	->show();
