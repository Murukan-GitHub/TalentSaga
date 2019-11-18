<?php if($paginator->lastPage() > 1): ?>
    <?php $previousPage = ($paginator->currentPage() > 1) ? $paginator->currentPage() - 1 : 1; ?>
    <ul class="pagination">
        <li><a class="<?php echo e(($paginator->currentPage() == 1) ? 'disabled' : ''); ?>" href="<?php echo e($paginator->url($previousPage)); ?>"><span class="fa fa-angle-left"></span></a></li>
        <?php for($i = 1; $i <= $paginator->lastPage(); $i++): ?>
            <li>
                <a class="<?php echo e(($paginator->currentPage() == $i) ? 'active' : ''); ?>" href="<?php echo e($paginator->url($i)); ?>">
                    <span><?php echo e($i); ?></span>
                </a>
            </li>
        <?php endfor; ?>
        <li><a class="<?php echo e(($paginator->currentPage() == $paginator->lastPage()) ? 'disabled' : ''); ?>" href="<?php echo e($paginator->url($paginator->currentPage()+1)); ?>"><span class="fa fa-angle-right"></span></a></li>
    </ul>
    <!-- pagination -->
<?php else: ?>
    <ul class="pagination">
        <li><a href="#"><span class="fa fa-angle-left"></span></a></li>
        <li><a class="active" href="#">1</a></li>
        <li><a href="#"><span class="fa fa-angle-right"></span></a></li>
    </ul>
    <!-- pagination -->
<?php endif; ?>
