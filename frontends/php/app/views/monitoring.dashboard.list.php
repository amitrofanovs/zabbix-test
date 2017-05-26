<?php
/*
** Zabbix
** Copyright (C) 2001-2017 Zabbix SIA
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


if ($data['uncheck']) {
	uncheckTableRows();
}
$createButton = (new CRedirectButton(_('Create Dashboard'), (new CUrl('zabbix.php'))
			->setArgument('action', 'dashboard.view')
			->setArgument('new', '1')
			->getUrl()))
	->setTitle(_('Create Dashboard'));

$widget = (new CWidget())
	->setTitle(_('Dashboards'))
	->setControls((new CForm())
		->cleanItems()
		->addItem($createButton)
		->addItem((new CList())
			->addItem(get_icon('fullscreen', ['fullscreen' => $data['fullscreen']]))
		)
	);

$form = (new CForm())
	->setName('dashboardForm');

$table = (new CTableInfo())
	->setHeader([
		(new CColHeader(
			(new CCheckBox('all_dashboards'))
				->onClick("checkAll('".$form->getName()."', 'all_dashboards', 'dashboardids');")
		))->addClass(ZBX_STYLE_CELL_WIDTH),
		make_sorting_header(_('Name'), 'name', $data['sort'], $data['sortorder'])
	]);

$url = (new CUrl('zabbix.php'))
	->setArgument('action', 'dashboard.view')
	->setArgument('dashboardid', '');
if ($data['fullscreen']) {
	$url->setArgument('fullscreen', '1');
}

foreach ($data['dashboards'] as $dashboard) {
	$table->addRow([
		(new CCheckBox('dashboardids['.$dashboard['dashboardid'].']', $dashboard['dashboardid']))
			->setEnabled($dashboard['editable']),
		new CLink($dashboard['name'],
			$url
				->setArgument('dashboardid', $dashboard['dashboardid'])
				->getUrl()
		)
	]);
}

$form->addItem([
	$table,
	$data['paging'],
	new CActionButtonList('action', 'dashboardids', [
		'dashboard.delete' => ['name' => _('Delete'), 'confirm' => _('Delete selected dashboards?')]
	], 'dashboard')
]);

$widget->addItem($form);
$widget->show();
