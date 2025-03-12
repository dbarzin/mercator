    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex justify-content-center" href="#">{{ (env('APP_NAME') === null) || (env('APP_NAME') === "Laravel") ? "Mercator" : env('APP_NAME') }}</a>
            <button class="btn toggle-sidebar-btn" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-3">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menu1" role="button" data-bs-toggle="dropdown" aria-expanded="false">Vues</a>
                        <ul class="dropdown-menu" aria-labelledby="menu1">
                            @can('gdpr_access')
                            <li><a class="dropdown-item" href="/admin/report/gdpr">
                                <i class="bi bi-diagram-3-fill"></i>{{ trans('panel.menu.gdpr') }}</a>
                            </li>
                            @endcan
                            @can('ecosystem_access')
                            <li><a class="dropdown-item" href="/admin/report/ecosystem">
                                <i class="bi bi-diagram-3-fill"></i>{{ trans('panel.menu.ecosystem') }}</a>
                            </li>
                            @endcan
                            @can('metier_access')
                            <li><a class="dropdown-item" href="/admin/report/information_system">
                                <i class="bi bi-diagram-3-fill"></i>{{ trans('panel.menu.information_system') }}</a>
                            </li>
                            @endcan
                            @can('application_access')
                            <li><a class="dropdown-item" href="/admin/report/applications">
                                <i class="bi bi-diagram-3-fill"></i>{{ trans('panel.menu.applications') }}</a>
                            </li>
                            @can('flux_access')
                            <li><a class="dropdown-item" href="/admin/report/application_flows">
                                <i class="bi bi-diagram-3-fill"></i>{{ trans('panel.menu.application_flows') }}</a>
                            </li>
                            @endcan
                            @endcan
                            @can('administration_access')
                            <li><a class="dropdown-item" href="/admin/report/administration">
                                <i class="bi bi-diagram-3-fill"></i>{{ trans('panel.menu.administration') }}</a>
                            </li>
                            @endcan
                            @can('infrastructure_access')
                            <li><a class="dropdown-item" href="/admin/report/logical_infrastructure">
                                <i class="bi bi-diagram-3-fill"></i>{{ trans('panel.menu.logical_infrastructure') }}</a>
                            </li>
                            @endcan
                            @can('physicalinfrastructure_access')
                            <li><a class="dropdown-item" href="/admin/report/physical_infrastructure">
                                <i class="bi bi-diagram-3-fill"></i>{{ trans('panel.menu.physical_infrastructure') }}</a>
                            </li>
                            @endcan
                            @can('physical_link_access')
                            <li><a class="dropdown-item" href="/admin/report/network_infrastructure">
                                <i class="bi bi-diagram-3-fill"></i>{{ trans('panel.menu.network_infrastructure') }}</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menu2" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ trans('panel.menu.preferences') }}</a>
                        <ul class="dropdown-menu" aria-labelledby="menu2">
                            <li><a class="dropdown-item" href="/profile/preferences">
                                <i class="bi bi-gear-fill"></i>{{ trans('panel.menu.options') }}</a>
                            </li>
                            @can('profile_password_edit')
                            <li><a class="dropdown-item" href="/profile/password">
                                <i class="bi bi-person-fill-lock"></i>{{ trans('panel.menu.password') }}</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menu3" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ trans('panel.menu.tools') }}</a>
                        <ul class="dropdown-menu" aria-labelledby="menu3">
                            @can('graph_access')
                            <li><a class="dropdown-item" href="/admin/graphs">
                                <i class="bi bi-map-fill"></i>{{ trans('cruds.graph.title') }}</a>
                            </li>
                            @endcan
                            <li><a class="dropdown-item" href="/admin/report/explore">
                                <i class="bi bi-globe2"></i>{{ trans('panel.menu.explore') }}</a>
                            </li>
                            @can('patching_access')
                            <li><a class="dropdown-item" href="/admin/patching/index">
                                <i class="bi bi-tools"></i>{{ trans('panel.menu.patching') }}</a>
                            </li>
                            @endcan
                            <li><a class="dropdown-item" href="/admin/doc/report">
                                <i class="bi bi-file-earmark-fill"></i>{{ trans('panel.menu.reports') }}</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menu4" role="button" data-bs-toggle="dropdown" aria-expanded="false">Aide</a>
                        <ul class="dropdown-menu" aria-labelledby="menu4">
                            <li><a class="dropdown-item" href="/admin/doc/schema">
                                <i class="bi bi-database-fill"></i>{{ trans('panel.menu.schema') }}</a>
                            </li>
                            <li><a class="dropdown-item" href="/admin/doc/guide">
                                <i class="bi bi-book-fill"></i>{{ trans('panel.menu.guide') }}</a>
                            </li>
                            <li><a class="dropdown-item" href="/admin/doc/about">
                                <i class="bi bi-info-circle-fill"></i>{{ trans('panel.menu.about') }}</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
