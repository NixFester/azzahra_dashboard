<?php $this->load->view('Template/header'); ?>
<style>
/* ===== MODERN TABLE DESIGN ===== */
.intro-y.datatable-wrapper {
    background: white;
    border-radius: 16px;
    box-shadow:
        0 10px 25px rgba(0, 0, 0, 0.08),
        0 4px 10px rgba(0, 0, 0, 0.04);
    border: 1px solid #f3f4f6;
    overflow: hidden;
    position: relative;
}

.intro-y.datatable-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #0041c3 0%, #0052e6 50%, #0063f0 100%);
}

/* ===== TABLE HEADER ===== */
.table thead th {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    color: #374151;
    font-weight: 700;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1.25rem 1rem;
    border-bottom: 2px solid #e5e7eb;
    position: relative;
}

.table thead th::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, #0041c3 0%, #0052e6 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.table thead th:hover::after {
    opacity: 1;
}

/* ===== TABLE BODY ===== */
.table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f9fafb;
}

.table tbody tr:hover {
    background: linear-gradient(135deg, #f8fafc 0%, #f0f9ff 100%);
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 65, 195, 0.08);
}

.table tbody td {
    padding: 1.25rem 1rem;
    color: #4b5563;
    font-size: 0.875rem;
    vertical-align: middle;
}

/* ===== STATUS BADGES ===== */
.table tbody td .text-gray-600 {
    background: #f3f4f6;
    color: #6b7280;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-block;
    margin-top: 0.25rem;
}

/* ===== ACTION BUTTONS ===== */
.table tbody td .flex.items-center a,
.table tbody td .flex.sm\:justify-center a {
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
}

.table tbody td .flex.items-center a:hover,
.table tbody td .flex.sm\:justify-center a:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* ===== SEARCH INPUT ===== */
.search-box {
    margin-bottom: 1.5rem;
}

.search-box input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.search-box input:focus {
    outline: none;
    border-color: #0041c3;
    box-shadow: 0 0 0 3px rgba(0, 65, 195, 0.1);
}

/* ===== PAGINATION ===== */
.pagination {
    display: flex;
    width: 100%;
    justify-content: center;
    align-items: center;
    margin: 2.5rem 0;
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 0.5rem 1rem;
    gap: 0.25rem;
    flex-wrap: wrap;
    box-shadow:
        0 8px 25px rgba(0, 0, 0, 0.06),
        0 4px 10px rgba(0, 0, 0, 0.04);
    position: relative;
    overflow: hidden;
    box-sizing: border-box;
}

.pagination::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg,
        rgba(0, 65, 195, 0.02) 0%,
        transparent 50%,
        rgba(0, 65, 195, 0.02) 100%);
    pointer-events: none;
}

/* ===== PAGINATION LINKS ===== */
.pagination-link,
.pagination-btn {
    min-width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    background: white;
    border: 1.5px solid #d1d5db;
    color: #4b5563;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

/* ===== PAGINATION NUMBER LINKS ===== */
.pagination-link:hover {
    color: #0041c3;
    background: linear-gradient(135deg, rgba(0, 65, 195, 0.08) 0%, rgba(0, 65, 195, 0.12) 100%);
    border-color: rgba(0, 65, 195, 0.3);
    transform: translateY(-3px) scale(1.05);
    box-shadow:
        0 8px 25px rgba(0, 65, 195, 0.15),
        0 4px 12px rgba(0, 65, 195, 0.1);
}

.pagination-link.active {
    color: white;
    background: linear-gradient(135deg, #0041c3 0%, #0052e6 100%);
    background-size: 200% 100%;
    border-color: #0041c3;
    font-weight: 700;
    box-shadow:
        0 8px 25px rgba(0, 65, 195, 0.3),
        0 4px 12px rgba(0, 65, 195, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
    animation: activePulse 2s ease-in-out infinite;
    cursor: default;
}

.pagination-link.active:hover {
    transform: none;
}

/* ===== PAGINATION BUTTONS (First/Prev/Next/Last) ===== */
.pagination-btn {
    background: linear-gradient(135deg, #0041c3 0%, #0052e6 100%);
    color: white;
    border-color: #0041c3;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0, 65, 195, 0.2);
}

.pagination-btn:hover {
    background: linear-gradient(135deg, #0052e6 0%, #0063f0 100%);
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 25px rgba(0, 65, 195, 0.25);
}

.pagination-first,
.pagination-last {
    padding: 0 1.5rem;
    min-width: 80px;
}

/* ===== ANIMATIONS ===== */
@keyframes activePulse {
    0%, 100% {
        background-position: 0% 50%;
        box-shadow:
            0 8px 25px rgba(0, 65, 195, 0.3),
            0 4px 12px rgba(0, 65, 195, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }
    50% {
        background-position: 100% 50%;
        box-shadow:
            0 10px 35px rgba(0, 65, 195, 0.4),
            0 6px 16px rgba(0, 65, 195, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .intro-y.datatable-wrapper {
        border-radius: 12px;
    }

    .table thead th {
        padding: 1rem 0.75rem;
        font-size: 0.75rem;
    }

    .table tbody td {
        padding: 1rem 0.75rem;
        font-size: 0.8rem;
    }

    .pagination {
        padding: 0.5rem 0.75rem;
        gap: 0.25rem;
        margin: 2rem 0;
    }

    .pagination-link,
    .pagination-btn {
        min-width: 32px;
        height: 32px;
        font-size: 0.7rem;
        padding: 0 0.5rem;
        border-radius: 6px;
    }

    .pagination-first,
    .pagination-last {
        padding: 0 1rem;
        min-width: 70px;
    }
}

@media (max-width: 480px) {
    .intro-y.datatable-wrapper {
        border-radius: 8px;
    }

    .table thead th {
        padding: 0.75rem 0.5rem;
        font-size: 0.7rem;
    }

    .table tbody td {
        padding: 0.75rem 0.5rem;
        font-size: 0.75rem;
    }

    .pagination {
        padding: 0.375rem 0.5rem;
        gap: 0.125rem;
    }

    .pagination-link,
    .pagination-btn {
        min-width: 28px;
        height: 28px;
        font-size: 0.65rem;
        padding: 0 0.375rem;
        border-radius: 4px;
    }

    .pagination-first,
    .pagination-last {
        display: none;
    }
}
</style>
        <!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1>Data Customer</h1>
                <p>Manage customer data</p>
            </div>
            <div class="header-actions">
                <div class="search-input-wrapper">
                    <i data-feather="search" class="search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search...">
                </div>
                <div class="header-btn">
                    <i data-feather="bell"></i>
                    <div class="badge-dot"></div>
                </div>
                <div class="header-btn">
                    <i data-feather="mail"></i>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content-area">
	<div class="login" data-login="<?php echo $this->session->flashdata('login');?>"></div>
	<div class="sukses" data-sukses="<?php echo $this->session->flashdata('sukses');?>"></div>
	<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Data Customer
        </h2>
    </div>
    <div class="intro-y datatable-wrapper box p-5 mt-5">
    	<!-- Search Input -->
    	<div class="search-box">
    		<input type="text" id="search-input" value="<?= $this->input->get('search') ?>" placeholder="Search customers..." class="form-input flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
    	</div>
    	<!-- Pagination Below Search -->
    	<div class="mt-3 mb-5" id="pagination-container-top">
    		<?php echo $pagination; ?>
    	</div>
    	<table class="table table-report table-report--bordered w-full">
    		<thead>
    			<tr>
    				<th class="border-b-2 text-center whitespace-no-wrap">NO</th>
    				<th class="border-b-2 text-center whitespace-no-wrap">INVOICE</th>
                    <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
                    <th class="border-b-2 whitespace-no-wrap">ALAMAT</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">NO HP</th>
                    <th class="border-b-2 whitespace-no-wrap">TANGGAL</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
    			</tr>
    		</thead>
    		<tbody id="table-body">
    			<?php
    				$no_urut = $no + 1;
    				foreach ($custom->result_array() as $row) : ?>
    					<tr>
    						<td class="text-center border-b"><?= $no_urut++?></td>
    						<td class="text-center border-b"><?= $row['cos_kode']?></td>
    		                <td class="border-b">
    		                	<div class="font-medium whitespace-no-wrap">
    		                		<?= $row['cos_nama']?>
    		                	</div>
    		                    <div class="text-gray-600 text-xs whitespace-no-wrap">
    		                    	STATUS : <?= $row['trans_status']?>
    		                    </div>
    		                </td>
    		                <td class="border-b"><?= $row['cos_alamat']?></td>
    		                <td class="text-center border-b"><?= $row['cos_hp']?></td>
    		                <td class="border-b">
    		                	<div class="font-medium whitespace-no-wrap">
    		                		<?php echo date('d-m-Y',strtotime($row['cos_tanggal'])) ?>
    		                	</div>
    		                    <div class="text-gray-600 text-xs whitespace-no-wrap">
    		                    	JAM : <?= $row['cos_jam']?>
    		                    </div>
    		                </td>
    		                <td class="border-b w-5">
    		                    <div class="flex sm:justify-center items-center">
    		                        <?php
    		                        	if ($row['trans_status'] == 'Return') { ?>
    		                        		<a href="<?= site_url('Kasir/trans_return/'.$row['trans_kode'])?>" class="button w-32 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">
    			    						<i data-feather="credit-card" class="w-4 h-4 mr-2"></i> Return
    			    					</a>
    		                        	<?php } else { ?>
    		                        		<a href="<?= site_url('Kasir/cari/'.$row['trans_kode'])?>" class="button w-32 mr-2 mb-2 flex items-center justify-center bg-theme-6 text-white">
    			    						<i data-feather="credit-card" class="w-4 h-4 mr-2"></i> Bayar
    			    					</a>
    		                        	<?php } ?>
    		                    </div>
    		                </td>
    		            </tr>
    				<?php endforeach; ?>
    		</tbody>
    	</table>
    	<div class="mt-5" id="pagination-container">
    		<?php echo $pagination; ?>
    	</div>
    </div>
</div>

<!-- Overlay for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

<script>
    var currentPage = 1;
    var currentSearch = '';

    // Wait for jQuery to be loaded
    function initSearch() {
        if (typeof $ === 'undefined') {
            setTimeout(initSearch, 100);
            return;
        }

        $('#search-input').on('keyup', function() {
            currentSearch = $(this).val();
            currentPage = 1;
            loadData();
        });

        // Initialize with current search if any
        currentSearch = $('#search-input').val();
    }

    window.addEventListener('load', initSearch);

    function loadPage(page) {
        currentPage = page;
        loadData();
    }

    function loadData() {
        $.ajax({
            url: '<?= site_url('Kasir/ajax_search') ?>',
            type: 'POST',
            data: {
                search: currentSearch,
                page: currentPage
            },
            dataType: 'json',
            success: function(response) {
                $('#table-body').html(response.table);
                $('#pagination-container').html(response.pagination);
                $('#pagination-container-top').html(response.pagination);
                // Reinitialize feather icons if needed
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            },
            error: function(xhr, status, error) {
                console.log('Error loading data:', error);
                console.log('Response:', xhr.responseText);
            }
        });
    }

    // Initialize Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Toggle Sidebar
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('collapsed');
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    }

    // Toggle Mobile Sidebar
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('mobile-active');
        overlay.classList.toggle('active');
    }

    // Remember sidebar state
    window.addEventListener('DOMContentLoaded', () => {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed && window.innerWidth > 1024) {
            document.getElementById('sidebar').classList.add('collapsed');
        }
    });

</script>

<?php $this->load->view('Template/footer'); ?>