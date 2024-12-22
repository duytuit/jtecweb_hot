@php $user = Auth::user(); @endphp

<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="mdi mdi-dots-horizontal"></i>
                    <span class="hide-menu">
                        {{ $user->first_name }}
                        <span class="badge badge-info">{{ $user->language ? $user->language->name : '' }}</span>
                    </span>
                </li>

                @if ($user->can('dashboard.view'))
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('admin.index') }}"
                        aria-expanded="false">
                        <i class="mdi mdi-creation"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                @endcan

                @if ($user->can('admin.view') || $user->can('admin.create') || $user->can('role.view') ||
                $user->can('role.create'))
                <li class="sidebar-item ">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="mdi mdi-account"></i>
                        <span class="hide-menu">Tài khoản & Quyền </span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ Route::is('admin.admins.index') || Route::is('admin.admins.create') || Route::is('admin.admins.edit') ? 'in' : null }}">
                        @if ($user->can('admin.view'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.admins.index') }}"
                                class="sidebar-link {{ Route::is('admin.admins.index') || Route::is('admin.admins.edit') ? 'active' : null }}">
                                <i class="mdi mdi-view-list"></i>
                                <span class="hide-menu"> Danh sách tài khoản </span>
                            </a>
                        </li>
                        @endcan

                        @if ($user->can('admin.create'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.admins.create') }}"
                                class="sidebar-link {{ Route::is('admin.admins.create') ? 'active' : null }}">
                                <i class="mdi mdi-plus-circle"></i>
                                <span class="hide-menu"> Thêm tài khoản </span>
                            </a>
                        </li>
                        @endcan

                        @if ($user->can('role.view'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.roles.index') }}"
                                class="sidebar-link {{ Route::is('admin.roles.index') ? 'active' : null }}">
                                <i class="mdi mdi-view-quilt"></i>
                                <span class="hide-menu"> Quyền </span>
                            </a>
                        </li>
                        @endcan

                        @if ($user->can('role.create'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.roles.create') }}"
                                class="sidebar-link {{ Route::is('admin.roles.create') ? 'active' : null }}">
                                <i class="mdi mdi-plus-circle"></i>
                                <span class="hide-menu"> Thêm quyền </span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @if ($user->can('tool.view') || $user->can('tool.create'))
                    <li class="sidebar-item ">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                            aria-expanded="false">
                            <i class="mdi mdi-view-list"></i>
                            <span class="hide-menu">Công cụ làm việc</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level {{ Route::is('admin.tools.index') || Route::is('admin.tools.create') || Route::is('admin.tools.edit') ? 'in' : null }}">
                            @if ($user->can('tool.view'))
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.tools.index') }}"
                                        class="sidebar-link {{ Route::is('admin.tools.index') || Route::is('admin.tools.edit') ? 'active' : null }}">
                                        <i class="mdi mdi-view-list"></i>
                                        <span class="hide-menu"> Danh sách</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if ($user->can('test_exam.view') || $user->can('test_exam.create'))
                    <li class="sidebar-item ">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                            aria-expanded="false">
                            <i class="mdi mdi-view-list"></i>
                            <span class="hide-menu">Bài thi online</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level {{ Route::is('admin.testExams.index') || Route::is('admin.testExams.create') || Route::is('admin.testExams.edit') ? 'in' : null }}">
                            @if ($user->can('test_exam.view'))
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.testExams.index') }}"
                                        class="sidebar-link {{ Route::is('admin.testExams.index') || Route::is('admin.testExams.edit') ? 'active' : null }}">
                                        <i class="mdi mdi-view-list"></i>
                                        <span class="hide-menu"> Danh sách</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if ($user->can('exam.view') || $user->can('exam.create'))
                    <li class="sidebar-item ">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                            aria-expanded="false">
                            <i class="mdi mdi-view-list"></i>
                            <span class="hide-menu">Kết quả thi trắc nghiệm</span>
                        </a>
                        <ul aria-expanded="false"
                            class="collapse first-level {{ Route::is('admin.exams.index') || Route::is('admin.exams.create') || Route::is('admin.exams.edit') ? 'in' : null }}">
                            @if ($user->can('exam.view'))
                            <li class="sidebar-item">
                                <a href="{{ route('admin.exams.index') }}"
                                    class="sidebar-link {{ Route::is('admin.exams.index') || Route::is('admin.exams.edit') ? 'active' : null }}">
                                    <i class="mdi mdi-view-list"></i>
                                    <span class="hide-menu"> Danh sách</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('admin.exams.audit') }}"
                                    class="sidebar-link {{ Route::is('admin.exams.audit') || Route::is('admin.exams.edit') ? 'active' : null }}">
                                    <i class="mdi mdi-view-list"></i>
                                    <span class="hide-menu">Kiểm tra công nhân mới</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if ($user->can('question.view') || $user->can('question.create'))
                    <li class="sidebar-item ">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                            aria-expanded="false">
                            <i class="mdi mdi-view-list"></i>
                            <span class="hide-menu">Câu hỏi thi trắc nghiệm</span>
                        </a>
                        <ul aria-expanded="false"
                            class="collapse first-level {{ Route::is('admin.questions.index') || Route::is('admin.questions.create') || Route::is('admin.questions.edit') ? 'in' : null }}">
                            @if ($user->can('question.view'))
                            <li class="sidebar-item">
                                <a href="{{ route('admin.questions.index') }}"
                                    class="sidebar-link {{ Route::is('admin.questions.index') || Route::is('admin.questions.edit') ? 'active' : null }}">
                                    <i class="mdi mdi-view-list"></i>
                                    <span class="hide-menu"> Danh sách </span>
                                </a>
                            </li>
                            @endif

                            @if ($user->can('question.create'))
                            <li class="sidebar-item">
                                <a href="{{ route('admin.questions.create') }}"
                                    class="sidebar-link {{ Route::is('admin.questions.create') ? 'active' : null }}">
                                    <i class="mdi mdi-plus-circle"></i>
                                    <span class="hide-menu"> Thêm mới </span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if ($user->can('checkCutMachine.view') || $user->can('checkCutMachine.create'))
                <li class="sidebar-item ">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="mdi mdi-view-list"></i>
                        <span class="hide-menu">Check list hàng ngày máy cắt</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ Route::is('admin.checkCutMachine.index') || Route::is('admin.checkCutMachine.create') || Route::is('admin.checkCutMachine.edit') ? 'in' : null }}">
                        @if ($user->can('checkCutMachine.view'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.checkCutMachine.index') }}"
                                class="sidebar-link {{ Route::is('admin.checkCutMachine.index') || Route::is('admin.checkCutMachine.edit') ? 'active' : null }}">
                                <i class="mdi mdi-view-list"></i>
                                <span class="hide-menu"> Danh sách check list </span>
                            </a>
                        </li>
                        @endif

                        @if ($user->can('checkCutMachine.create'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.checkCutMachine.create') }}"
                                class="sidebar-link {{ Route::is('admin.checkCutMachine.create') ? 'active' : null }}">
                                <i class="mdi mdi-plus-circle"></i>
                                <span class="hide-menu"> Thêm check list</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if ($user->can('requestForm.view') || $user->can('requestForm.create'))
                    <li class="sidebar-item ">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                            aria-expanded="false">
                            <i class="mdi mdi-view-list"></i>
                            <span class="hide-menu">Phiếu Yêu Cầu</span>
                        </a>
                        <ul aria-expanded="false"
                            class="collapse first-level {{ Route::is('admin.requestForms.index') || Route::is('admin.requestForms.create') || Route::is('admin.requestForms.edit') ? 'in' : null }}">
                            @if ($user->can('requestForm.view'))
                            <li class="sidebar-item">
                                <a href="{{ route('admin.requestForms.index') }}"
                                    class="sidebar-link {{ Route::is('admin.requestForms.index') || Route::is('admin.requestForms.edit') ? 'active' : null }}">
                                    <i class="mdi mdi-view-list"></i>
                                    <span class="hide-menu"> Danh sách</span>
                                </a>
                            </li>
                            @endif

                            @if ($user->can('requestForm.create'))
                            <li class="sidebar-item">
                                <a href="{{ route('admin.requestForms.create') }}"
                                    class="sidebar-link {{ Route::is('admin.requestForms.create') ? 'active' : null }}">
                                    <i class="mdi mdi-plus-circle"></i>
                                    <span class="hide-menu"> Thêm yêu cầu</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if ($user->can('cutedp.view') || $user->can('cutedp.create'))
                    <li class="sidebar-item ">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                            aria-expanded="false">
                            <i class="mdi mdi-view-list"></i>
                            <span class="hide-menu">Yêu cầu cắt EDP</span>
                        </a>
                        <ul aria-expanded="false"
                            class="collapse first-level {{ Route::is('admin.cutedps.index') || Route::is('admin.cutedps.create') || Route::is('admin.cutedps.edit') ? 'in' : null }}">
                            @if ($user->can('cutedp.create'))
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.cutedps.create') }}"
                                        class="sidebar-link {{ Route::is('admin.cutedps.create') ? 'active' : null }}">
                                        <i class="mdi mdi-plus-circle"></i>
                                        <span class="hide-menu"> Thêm yêu cầu</span>
                                    </a>
                                </li>
                            @endif
                            @if ($user->can('cutedp.view'))
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.cutedps.index') }}"
                                        class="sidebar-link {{ Route::is('admin.cutedps.index') || Route::is('admin.cutedps.edit') ? 'active' : null }}">
                                        <i class="mdi mdi-view-list"></i>
                                        <span class="hide-menu"> Danh sách</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if ($user->can('department.view') || $user->can('department.create'))
                <li class="sidebar-item ">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="mdi mdi-view-list"></i>
                        <span class="hide-menu">Bộ phận</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ Route::is('admin.departments.index') || Route::is('admin.departments.create') || Route::is('admin.departments.edit') ? 'in' : null }}">
                        @if ($user->can('department.view'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.departments.index') }}"
                                class="sidebar-link {{ Route::is('admin.departments.index') || Route::is('admin.departments.edit') ? 'active' : null }}">
                                <i class="mdi mdi-view-list"></i>
                                <span class="hide-menu"> Danh sách </span>
                            </a>
                        </li>
                        @endif

                        @if ($user->can('department.create'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.departments.create') }}"
                                class="sidebar-link {{ Route::is('admin.departments.create') ? 'active' : null }}">
                                <i class="mdi mdi-plus-circle"></i>
                                <span class="hide-menu"> Thêm mới </span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if ($user->can('productionPlan.view') || $user->can('productionPlan.create'))
                    <li class="sidebar-item ">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                            aria-expanded="false">
                            <i class="mdi mdi-view-list"></i>
                            <span class="hide-menu">Kế hoạch sản xuất</span>
                        </a>
                        <ul aria-expanded="false"
                            class="collapse first-level {{ Route::is('admin.productionPlans.index') || Route::is('admin.productionPlans.create') || Route::is('admin.productionPlans.edit') ? 'in' : null }}">
                            @if ($user->can('productionPlan.view'))
                            <li class="sidebar-item">
                                <a href="{{ route('admin.productionPlans.index') }}"
                                    class="sidebar-link {{ Route::is('admin.productionPlans.index') || Route::is('admin.productionPlans.edit') ? 'active' : null }}">
                                    <i class="mdi mdi-view-list"></i>
                                    <span class="hide-menu"> Danh sách </span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if ($user->can('dynamicColumn.view') || $user->can('dynamicColumn.create'))
                    <li class="sidebar-item ">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                            aria-expanded="false">
                            <i class="mdi mdi-view-list"></i>
                            <span class="hide-menu">Quản lý tên cột</span>
                        </a>
                        <ul aria-expanded="false"
                            class="collapse first-level {{ Route::is('admin.dynamicColumns.index') || Route::is('admin.dynamicColumns.create') || Route::is('admin.dynamicColumns.edit') ? 'in' : null }}">
                            @if ($user->can('dynamicColumn.view'))
                            <li class="sidebar-item">
                                <a href="{{ route('admin.dynamicColumns.index') }}"
                                    class="sidebar-link {{ Route::is('admin.dynamicColumns.index') || Route::is('admin.dynamicColumns.edit') ? 'active' : null }}">
                                    <i class="mdi mdi-view-list"></i>
                                    <span class="hide-menu"> Danh sách </span>
                                </a>
                            </li>
                            @endif
                            @if ($user->can('dynamicColumn.create'))
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.assets.create') }}"
                                        class="sidebar-link {{ Route::is('admin.assets.create') ? 'active' : null }}">
                                        <i class="mdi mdi-plus-circle"></i>
                                        <span class="hide-menu"> Thêm mới </span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if ($user->can('asset.view') || $user->can('asset.create'))
                <li class="sidebar-item ">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="mdi mdi-view-list"></i>
                        <span class="hide-menu">Tài sản</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ Route::is('admin.assets.index') || Route::is('admin.assets.create') || Route::is('admin.assets.edit') ? 'in' : null }}">
                        @if ($user->can('asset.view'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.assets.index') }}"
                                class="sidebar-link {{ Route::is('admin.assets.index') || Route::is('admin.assets.edit') ? 'active' : null }}">
                                <i class="mdi mdi-view-list"></i>
                                <span class="hide-menu"> Danh sách </span>
                            </a>
                        </li>
                        @endif

                        @if ($user->can('asset.create'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.assets.create') }}"
                                class="sidebar-link {{ Route::is('admin.assets.create') ? 'active' : null }}">
                                <i class="mdi mdi-plus-circle"></i>
                                <span class="hide-menu"> Thêm mới </span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if ($user->can('assemble.view') || $user->can('assemble.create'))
                    <li class="sidebar-item ">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                            aria-expanded="false">
                            <i class="mdi mdi-view-list"></i>
                            <span class="hide-menu">Lắp ráp</span>
                        </a>
                        <ul aria-expanded="false"
                            class="collapse first-level {{ Route::is('admin.assembles.index') || Route::is('admin.assembles.create') || Route::is('admin.assembles.edit') ? 'in' : null }}">
                            @if ($user->can('assemble.view'))
                            <li class="sidebar-item">
                                <a href="{{ route('admin.assembles.index') }}"
                                    class="sidebar-link {{ Route::is('admin.assembles.index') || Route::is('admin.assembles.edit') ? 'active' : null }}">
                                    <i class="mdi mdi-view-list"></i>
                                    <span class="hide-menu"> Danh sách </span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if ($user->can('employee.view') || $user->can('employee.create'))
                <li class="sidebar-item ">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="mdi mdi-view-list"></i>
                        <span class="hide-menu">Nhân viên</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ Route::is('admin.employees.index') || Route::is('admin.employees.create') || Route::is('admin.employees.edit') ? 'in' : null }}">
                        @if ($user->can('employee.view'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.employees.index') }}"
                                class="sidebar-link {{ Route::is('admin.employees.index') || Route::is('admin.employees.edit') ? 'active' : null }}">
                                <i class="mdi mdi-view-list"></i>
                                <span class="hide-menu"> Danh sách nhân viên </span>
                            </a>
                        </li>
                        @endif

                        @if ($user->can('employee.create'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.employees.create') }}"
                                class="sidebar-link {{ Route::is('admin.employees.create') ? 'active' : null }}">
                                <i class="mdi mdi-plus-circle"></i>
                                <span class="hide-menu"> Thêm nhân viên </span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if ($user->can('accessory.view') || $user->can('accessory.create'))
                <li class="sidebar-item ">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="mdi mdi-view-list"></i>
                        <span class="hide-menu">Linh kiện</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ Route::is('admin.accessorys.index') || Route::is('admin.accessorys.create') || Route::is('admin.accessorys.edit') ? 'in' : null }}">
                        @if ($user->can('accessory.view'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.accessorys.index') }}"
                                class="sidebar-link {{ Route::is('admin.accessorys.index') || Route::is('admin.accessorys.edit') ? 'active' : null }}">
                                <i class="mdi mdi-view-list"></i>
                                <span class="hide-menu"> Danh sách </span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if ($user->can('requestVpp.view') || $user->can('requestVpp.create'))
                <li class="sidebar-item ">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="mdi mdi-view-list"></i>
                        <span class="hide-menu">Yêu cầu VPP</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ Route::is('admin.requestVpps.index') || Route::is('admin.requestVpps.create') || Route::is('admin.requestVpps.edit') || Route::is('admin.requestVpps.report') ||  Route::is('admin.requestVpps.requiredWithDelete') ? 'in' : null }}">
                        @if ($user->can('requestVpp.create'))
                            <li class="sidebar-item">
                                <a href="{{ route('admin.requestVpps.create') }}"
                                    class="sidebar-link {{ Route::is('admin.requestVpps.create') ? 'active' : null }}">
                                    <i class="mdi mdi-plus-circle"></i>
                                    <span class="hide-menu"> Thêm yêu cầu </span>
                                </a>
                            </li>
                        @endif
                        @if ($user->can('requestVpp.view'))
                            <li class="sidebar-item">
                                <a href="{{ route('admin.requestVpps.index') }}"
                                    class="sidebar-link {{ Route::is('admin.requestVpps.index') || Route::is('admin.requestVpps.edit') ? 'active' : null }}">
                                    <i class="mdi mdi-view-list"></i>
                                    <span class="hide-menu"> Yêu cầu trong ngày</span>
                                </a>
                            </li>
                            @if ($user->can('requestVpp.confirm'))
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.requestVpps.indexConfirm') }}"
                                        class="sidebar-link {{ Route::is('admin.requestVpps.indexConfirm') || Route::is('admin.requestVpps.edit') ? 'active' : null }}">
                                        <i class="mdi mdi-view-list"></i>
                                        <span class="hide-menu"> Yêu cầu cần duyệt</span>
                                    </a>
                                </li>
                            @endif
                            <li class="sidebar-item">
                                <a href="{{ route('admin.requestVpps.report') }}"
                                    class="sidebar-link {{ Route::is('admin.requestVpps.report') || Route::is('admin.requestVpps.edit') ? 'active' : null }}">
                                    <i class="mdi mdi-view-list"></i>
                                    <span class="hide-menu"> Báo cáo </span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('admin.requestVpps.requiredWithDelete') }}"
                                    class="sidebar-link {{ Route::is('admin.requestVpps.requiredWithDelete') || Route::is('admin.requestVpps.edit') ? 'active' : null }}">
                                    <i class="mdi mdi-view-list"></i>
                                    <span class="hide-menu"> Yêu cầu đã xóa </span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if ($user->can('required.view') || $user->can('required.create'))
                <li class="sidebar-item ">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="mdi mdi-view-list"></i>
                        <span class="hide-menu">Yêu cầu linh kiện</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ Route::is('admin.requireds.index') || Route::is('admin.requireds.create') || Route::is('admin.requireds.edit') || Route::is('admin.requireds.report') ||  Route::is('admin.requireds.requiredWithDelete') ? 'in' : null }}">
                        @if ($user->can('required.create'))
                            <li class="sidebar-item">
                                <a href="{{ route('admin.requireds.create') }}"
                                    class="sidebar-link {{ Route::is('admin.requireds.create') ? 'active' : null }}">
                                    <i class="mdi mdi-plus-circle"></i>
                                    <span class="hide-menu"> Thêm yêu cầu </span>
                                </a>
                            </li>
                        @endif
                        @if ($user->can('required.view'))
                            <li class="sidebar-item">
                                <a href="{{ route('admin.requireds.index') }}"
                                    class="sidebar-link {{ Route::is('admin.requireds.index') || Route::is('admin.requireds.edit') ? 'active' : null }}">
                                    <i class="mdi mdi-view-list"></i>
                                    <span class="hide-menu"> Yêu cầu trong ngày</span>
                                </a>
                            </li>
                            @if ($user->can('required.confirm'))
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.requireds.indexConfirm') }}"
                                        class="sidebar-link {{ Route::is('admin.requireds.indexConfirm') || Route::is('admin.requireds.edit') ? 'active' : null }}">
                                        <i class="mdi mdi-view-list"></i>
                                        <span class="hide-menu"> Yêu cầu cần duyệt</span>
                                    </a>
                                </li>
                            @endif
                            <li class="sidebar-item">
                                <a href="{{ route('admin.requireds.report') }}"
                                    class="sidebar-link {{ Route::is('admin.requireds.report') || Route::is('admin.requireds.edit') ? 'active' : null }}">
                                    <i class="mdi mdi-view-list"></i>
                                    <span class="hide-menu"> Báo cáo </span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('admin.requireds.requiredWithDelete') }}"
                                    class="sidebar-link {{ Route::is('admin.requireds.requiredWithDelete') || Route::is('admin.requireds.edit') ? 'active' : null }}">
                                    <i class="mdi mdi-view-list"></i>
                                    <span class="hide-menu"> Yêu cầu đã xóa </span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if ($user->can('warehouse.view') || $user->can('warehouse.create'))
                    <li class="sidebar-item ">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                            aria-expanded="false">
                            <i class="mdi mdi-view-list"></i>
                            <span class="hide-menu">Xuất linh kiện</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level {{Route::is('admin.warehouses.index_ong') || Route::is('admin.warehouses.index') || Route::is('admin.warehouses.create') || Route::is('admin.warehouses.edit') || Route::is('admin.warehouses.report') ? 'in' : null }}">
                            @if ($user->can('warehouse.view'))
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.warehouses.index') }}"
                                        class="sidebar-link {{ Route::is('admin.warehouses.index') || Route::is('admin.warehouses.edit') ? 'active' : null }}">
                                        <i class="mdi mdi-view-list"></i>
                                        <span class="hide-menu"> Xuất dây Điện, Tanshi </span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.warehouses.report') }}"
                                        class="sidebar-link {{ Route::is('admin.warehouses.report') || Route::is('admin.warehouses.edit') ? 'active' : null }}">
                                        <i class="mdi mdi-view-list"></i>
                                        <span class="hide-menu"> Báo cáo </span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.warehouses.index_ong') }}"
                                        class="sidebar-link {{ Route::is('admin.warehouses.index_ong') || Route::is('admin.warehouses.edit') ? 'active' : null }}">
                                        <i class="mdi mdi-view-list"></i>
                                        <span class="hide-menu"> Xuất ống,băng dính,keo,thiếc</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                    <li class="sidebar-item ">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                            aria-expanded="false">
                            <i class="mdi mdi-view-list"></i>
                            <span class="hide-menu">Xuất linh kiện New</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level {{Route::is('admin.warehouse_v2.index_ong') || Route::is('admin.warehouse_v2.index') || Route::is('admin.warehouse_v2.create') || Route::is('admin.warehouse_v2.edit') || Route::is('admin.warehouse_v2.report') ? 'in' : null }}">

                                <li class="sidebar-item">
                                    <a href="{{ route('admin.warehouse_v2.index') }}"
                                        class="sidebar-link {{ Route::is('admin.warehouse_v2.index') || Route::is('admin.warehouse_v2.edit') ? 'active' : null }}">
                                        <i class="mdi mdi-view-list"></i>
                                        <span class="hide-menu"> Xuất dây Điện, Tanshi </span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.warehouse_v2.report') }}"
                                        class="sidebar-link {{ Route::is('admin.warehouse_v2.report') || Route::is('admin.warehouse_v2.edit') ? 'active' : null }}">
                                        <i class="mdi mdi-view-list"></i>
                                        <span class="hide-menu"> Báo cáo </span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.warehouse_v2.index_ong') }}"
                                        class="sidebar-link {{ Route::is('admin.warehouse_v2.index_ong') || Route::is('admin.warehouse_v2.edit') ? 'active' : null }}">
                                        <i class="mdi mdi-view-list"></i>
                                        <span class="hide-menu"> Xuất ống,băng dính,keo,thiếc</span>
                                    </a>
                                </li>
                        </ul>
                    </li>

                @if ($user->can('checkdevice.view') || $user->can('checkdevice.create'))
                <li class="sidebar-item ">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="mdi mdi-view-list"></i>
                        <span class="hide-menu">Kiểm tra thiết bị</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ Route::is('admin.checkdevices.index_list') || Route::is('admin.checkdevices.create') || Route::is('admin.checkdevices.edit') ? 'in' : null }}">
                        @if ($user->can('checkdevice.view'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.checkdevices.index_list') }}"
                                class="sidebar-link {{ Route::is('admin.checkdevices.index_list') || Route::is('admin.checkdevices.edit') ? 'active' : null }}">
                                <i class="mdi mdi-view-list"></i>
                                <span class="hide-menu"> Danh sách </span>
                            </a>
                        </li>
                        @endif

                        @if ($user->can('checkdevice.create'))
                            <li class="sidebar-item">
                                <a href="{{ route('admin.checkdevices.checklist_realtime') }}"
                                    class="sidebar-link {{ Route::is('admin.checkdevices.checklist_realtime') ? 'active' : null }}">
                                    <i class="mdi mdi-plus-circle"></i>
                                    <span class="hide-menu">Giám sát thiết bị</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if ($user->can('upload_data.view') || $user->can('upload_data.create'))
                <li class="sidebar-item ">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="mdi mdi-view-list"></i>
                        <span class="hide-menu">Dữ liệu bàn gá</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ Route::is('admin.uploadDatas.index') || Route::is('admin.uploadDatas.create') || Route::is('admin.uploadDatas.edit') ? 'in' : null }}">
                        @if ($user->can('upload_data.view'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.uploadDatas.index') }}"
                                class="sidebar-link {{ Route::is('admin.uploadDatas.index') || Route::is('admin.uploadDatas.edit') ? 'active' : null }}">
                                <i class="mdi mdi-view-list"></i>
                                <span class="hide-menu"> Danh sách </span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if ($user->can('signature_submission.view') || $user->can('signature_submission.create'))
                <li class="sidebar-item ">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="mdi mdi-view-list"></i>
                        <span class="hide-menu">Trình ký</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ Route::is('admin.signatureSubmissions.index') || Route::is('admin.signatureSubmissions.create') || Route::is('admin.signatureSubmissions.edit') ? 'in' : null }}">
                        @if ($user->can('signature_submission.view'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.signatureSubmissions.index') }}"
                                class="sidebar-link {{ Route::is('admin.signatureSubmissions.index') || Route::is('admin.signatureSubmissions.edit') ? 'active' : null }}">
                                <i class="mdi mdi-view-list"></i>
                                <span class="hide-menu"> Danh sách </span>
                            </a>
                        </li>
                        @endif

                        @if ($user->can('signature_submission.create'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.signatureSubmissions.create') }}"
                                class="sidebar-link {{ Route::is('admin.signatureSubmissions.create') ? 'active' : null }}">
                                <i class="mdi mdi-plus-circle"></i>
                                <span class="hide-menu"> Thêm mới </span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if ($user->can('activity.create'))
                <li class="sidebar-item ">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="mdi mdi-view-list"></i>
                        <span class="hide-menu">Lịch sử thao tác</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ Route::is('admin.activitys.index') || Route::is('admin.activitys.create') || Route::is('admin.activitys.edit') ? 'in' : null }}">
                        <li class="sidebar-item">
                            <a href="{{ route('admin.activitys.index') }}"
                                class="sidebar-link {{ Route::is('admin.activitys.index') || Route::is('admin.activitys.edit') ? 'active' : null }}">
                                <i class="mdi mdi-view-list"></i>
                                <span class="hide-menu"> Danh sách </span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if ($user->can('log_import.view') || $user->can('log_import.create'))
                <li class="sidebar-item ">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="mdi mdi-view-list"></i>
                        <span class="hide-menu">Lịch sử Import</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ Route::is('admin.logImports.index') || Route::is('admin.logImports.create') || Route::is('admin.logImports.edit') ? 'in' : null }}">
                        @if ($user->can('log_import.view'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.logImports.index') }}"
                                class="sidebar-link {{ Route::is('admin.logImports.index') || Route::is('admin.logImports.edit') ? 'active' : null }}">
                                <i class="mdi mdi-view-list"></i>
                                <span class="hide-menu"> Danh sách </span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                <li class="sidebar-item ">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                        aria-expanded="false">
                        <i class="mdi mdi-settings"></i>
                        <span class="hide-menu">Cấu hình hệ thống</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ Route::is('admin.languages.index') || Route::is('admin.languages.create') || Route::is('admin.languages.edit') || Route::is('admin.languages.connection.index') ? 'in' : null }}">
                        <li class="sidebar-item">
                            <a href="{{ route('admin.languages.index') }}"
                                class="sidebar-link {{ Route::is('admin.languages.index') || Route::is('admin.languages.create') || Route::is('admin.languages.edit') ? 'active' : null }}">
                                <i class="mdi mdi-plus-circle"></i>
                                <span class="hide-menu"> Ngôn ngữ </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.settings.index') }}"
                                class="sidebar-link {{ Route::is('admin.settings.index') ? 'active' : null }}">
                                <i class="mdi mdi-settings"></i>
                                <span class="hide-menu"> Cài đặt </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('admin.logout') }}"
                        onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();" aria-expanded="false">
                        <i class="mdi mdi-directions"></i>
                        <span class="hide-menu">Đăng xuất</span>
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
