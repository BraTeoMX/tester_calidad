<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-normal"><?php echo e(_('INTIMARK')); ?></a>
        </div>
        <ul class="nav">
            <?php if(auth()->check() && (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Gerente de Calidad'))): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('home')); ?>">
                        <i class="tim-icons icon-chart-pie-36"></i>
                        <p><?php echo e(__('Dashboard')); ?></p>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(auth()->check() && (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Gerente de Calidad'))): ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#laravelExample" aria-expanded="true">
                        <i class="material-icons">admin_panel_settings</i>
                        <p><?php echo e(__('Admin cuentas')); ?>

                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="laravelExample">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo e(route('profile.edit')); ?>">
                                    <i class="tim-icons icon-single-02"></i>
                                    <span class="sidebar-normal"><?php echo e(__('User profile')); ?> </span>
                                </a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="<?php echo e(route('user.index')); ?>">
                                    <i class="tim-icons icon-single-02"></i>
                                    <span class="sidebar-normal"> <?php echo e(__('User Management')); ?> </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>
            <li class="nav-item <?php echo e($pageSlug == 'profile' || $pageSlug == 'user-management' ? ' active' : ''); ?>">
                <a class="nav-link" data-toggle="collapse" href="#laravelExamples" aria-expanded="true">
                    <i class="material-icons">note_alt</i>
                    <p><?php echo e(__('Formularios Calidad')); ?>

                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="laravelExamples">
                    <ul class="nav">
                        <?php if(auth()->check() && (auth()->user()->hasRole('Auditor') || auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Gerente de Calidad')) && auth()->user()->Planta == 'Planta1'): ?>
                        <li class="nav-item<?php echo e($pageSlug == 'Etiquetas' ? ' active' : ''); ?>">
                            <a class="nav-link" href="<?php echo e(route('formulariosCalidad.auditoriaEtiquetas')); ?>">
                                <i class="material-icons">edit_document</i>
                                <p><?php echo e(__('FCC-014')); ?></p>
                                <p style="text-align: center;"><?php echo e(__('AUDITORIA ETIQUETAS')); ?></p>
                            </a>
                        </li>
                        <li class="nav-item<?php echo e($pageSlug == 'Progreso Corte' ? ' active' : ''); ?>">
                            <a class="nav-link" href="<?php echo e(route('auditoriaCorte.inicioAuditoriaCorte')); ?>">
                                <i class="material-icons">edit_document</i>
                                <p><?php echo e(__('FCC-010')); ?></p>
                                <p style="text-align: center;"><?php echo e(__('AUDITORIA CORTE')); ?></p>
                            </a>
                        </li>
                        <li class="nav-item<?php echo e($pageSlug == 'Evaluacion Corte' ? ' active' : ''); ?>">
                            <a class="nav-link" href="<?php echo e(route('evaluacionCorte.inicioEvaluacionCorte')); ?>">
                                <i class="material-icons">edit_document</i>
                                <p><?php echo e(__('F-4')); ?></p>
                                <p style="text-align: center;"><?php echo e(__('EVALUACION DE CORTE')); ?></p>
                            </a>
                        </li>
                        <li class="nav-item<?php echo e($pageSlug == 'Proceso Corte' ? ' active' : ''); ?>">
                            <a class="nav-link" href="<?php echo e(route('auditoriaProcesoCorte.altaProcesoCorte')); ?>">
                                <i class="material-icons">edit_document</i>
                                <p><?php echo e(__('FCC-04')); ?></p>
                                <p style="text-align: center;"><?php echo e(__('AUDITORIA PROCESO DE CORTE')); ?></p>
                            </a>
                        </li>
                        <li class="nav-item<?php echo e($pageSlug == 'proceso' ? ' active' : ''); ?>">
                            <a class="nav-link" href="<?php echo e(route('aseguramientoCalidad.altaProceso')); ?>">
                                <i class="material-icons">edit_document</i>
                                <p><?php echo e(__('FCC-001')); ?></p>
                                <p style="text-align: center;"><?php echo e(__('AUDITORIA DE PROCESOS')); ?></p>
                            </a>
                        </li>
                        <li class="nav-item<?php echo e($pageSlug == 'AQL' ? ' active' : ''); ?>">
                            <a class="nav-link" href="<?php echo e(route('auditoriaAQL.altaAQL')); ?>">
                                <i class="material-icons">edit_document</i>
                                <p><?php echo e(__('FCC-009-B')); ?></p>
                                <p style="text-align: center;"><?php echo e(__('AUDITORIA FINAL A.Q.L')); ?></p>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if(auth()->check() && (auth()->user()->hasRole('Auditor') || auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Gerente de Calidad')) && auth()->user()->Planta == 'Planta2'): ?>
                    <li class="nav-item<?php echo e($pageSlug == 'ScreenPrint' ? ' active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('ScreenPlanta2.ScreenPrint')); ?>">
                            <i class="material-icons">edit_document</i>
                            <p><?php echo e(__('Screen Print')); ?></p>
                        </a>
                    </li>
                    <li class="nav-item<?php echo e($pageSlug == 'InspeccionEstampado' ? ' active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('ScreenPlanta2.InsEstamHorno')); ?>">
                            <i class="material-icons">edit_document</i>
                            <p><?php echo e(__('Inspección Después De Horno')); ?></p>
                        </a>
                    </li>
                    <li class="nav-item<?php echo e($pageSlug == 'CalidadProcesoPlancha' ? ' active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('ScreenPlanta2.CalidadProcesoPlancha')); ?>">
                            <i class="material-icons">edit_document</i>
                            <p><?php echo e(__('Proceso Plancha')); ?></p>
                        </a>
                    </li>
                    <li class="nav-item<?php echo e($pageSlug == 'Maquila' ? ' active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('ScreenPlanta2.Maquila')); ?>">
                            <i class="material-icons">edit_document</i>
                            <p><?php echo e(__('Maquila')); ?></p>
                        </a>
                    </li>
                    <li class="nav-item<?php echo e($pageSlug == 'proceso' ? ' active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('aseguramientoCalidad.altaProceso')); ?>">
                            <i class="material-icons">edit_document</i>
                            <p><?php echo e(__('FCC-001')); ?></p>
                            <p style="text-align: center;"><?php echo e(__('AUDITORIA DE PROCESOS')); ?></p>
                        </a>
                    </li>
                    <li class="nav-item<?php echo e($pageSlug == 'AQL' ? ' active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('auditoriaAQL.altaAQL')); ?>">
                            <i class="material-icons">edit_document</i>
                            <p><?php echo e(__('FCC-009-B')); ?></p>
                            <p style="text-align: center;"><?php echo e(__('AUDITORIA FINAL A.Q.L')); ?></p>
                        </a>
                    </li>
                <?php endif; ?>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\calidad2\resources\views/layouts/navbars/sidebar.blade.php ENDPATH**/ ?>