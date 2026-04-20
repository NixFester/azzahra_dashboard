<?php $this->load->view('Template/header'); ?>
<?php if (!isset($role)) $role = 'kasir'; ?>
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
                <h1><i data-feather="credit-card" class="w-6 h-6 inline-block mr-2"></i>Pembayaran</h1>
                <p>Manage payment data</p>
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
	<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Data Pembayaran
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <a role="button" class="button text-white bg-theme-1 shadow-md mr-2">Pembayaran hari ini
            </a> 
            <div class="pos-dropdown dropdown relative ml-auto sm:ml-0">
                <button class="dropdown-toggle button px-2 box text-gray-700">
                    <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-feather="chevron-down"></i> </span>
                </button>
                <div class="pos-dropdown__dropdown-box dropdown-box mt-10 absolute top-0 right-0 z-20">
                    <div class="dropdown-box__content box p-2">
                         <?php
                        foreach ($lap_bayar->result_array() as $lap) :?>
                            <a href="<?= site_url('Kasir/cari/'.$lap['trans_kode'])?>" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md">
                                <i data-feather="activity" class="w-4 h-4 mr-2"></i> 
                                <span class="truncate"><?= $lap['cos_kode'];?> - <?= $lap['cos_nama'];?></span> 
                            </a>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <!-- BEGIN: Item List -->
        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="grid grid-cols-12 gap-5 mt-5">
                <div class="col-span-12 sm:col-span-3 xxl:col-span-3 box bg-theme-1 p-5 cursor-pointer zoom-in" onclick="window.location='<?= site_url(($role == 'cs' ? 'Service' : 'Kasir').'/pembayaran/dp')?>'">
                    <div class="font-medium text-base text-white">Down Payment (DP)</div>
                    <div class="text-theme-25">
                        <?php
                        $dp = $this->db->get_where('transaksi', array('trans_status'=>'Pelunasan'));
                        ?>
                        <?= $dp->num_rows()?> Customer
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-3 xxl:col-span-3 box bg-theme-1 p-5 cursor-pointer zoom-in" onclick="window.location='<?= site_url(($role == 'cs' ? 'Service' : 'Kasir').'/pembayaran/lunas')?>'">
                    <div class="font-medium text-base text-white">Lunas</div>
                    <div class="text-theme-25">
                        <?php
                        $lunas = $this->db->get_where('transaksi', array('trans_status'=>'Lunas'));
                        ?>
                        <?= $lunas->num_rows()?> Customer
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-3 xxl:col-span-3 box bg-theme-1 p-5 cursor-pointer zoom-in" onclick="window.location='<?= site_url(($role == 'cs' ? 'Service' : 'Kasir').'/pembayaran')?>'">
                    <div class="font-medium text-base text-white">Jumlah Customer</div>
                    <div class="text-theme-25">
                        <?= $dp->num_rows() + $lunas->num_rows() ?> Customer
                    </div>
                </div>
            </div>
            <div class="intro-y datatable-wrapper box p-5 mt-5">
                <h2 class="text-lg font-medium mr-auto">
                    <?php
                    $title_text = 'Data Customer';
                    if (isset($filter)) {
                        if ($filter == 'dp') $title_text = 'Data Customer - Down Payment';
                        elseif ($filter == 'lunas') $title_text = 'Data Customer - Lunas';
                    }
                    echo $title_text;
                    ?>
                </h2>
                <!-- Search Input -->
                <div class="search-box" style="margin-top: 1rem; margin-bottom: 1.5rem;">
                    <input type="text" id="search-input-pembayaran" value="<?= $this->input->get('search') ?>" placeholder="Search customers..." class="form-input flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" style="width: 100%;">
                </div>
                <!-- Pagination Below Search -->
                <div class="mt-3 mb-5" id="pagination-container-top-pembayaran">
                    <?php echo $pagination; ?>
                </div>
            	<table class="table table-report table-report--bordered w-full">
            		<thead>
         <tr>
          <th class="border-b-2 text-center whitespace-no-wrap">NO</th>
          <th class="border-b-2 text-center whitespace-no-wrap">INVOICE</th>
            	         <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
            	         <th class="border-b-2 text-center whitespace-no-wrap">MODEL UNIT</th>
            	         <th class="border-b-2 text-center whitespace-no-wrap">STATUS</th>
            	         <th class="border-b-2 text-center whitespace-no-wrap">TANGGAL</th>
         </tr>
        </thead>
        <tbody id="table-body-pembayaran">
         <?php
         $no = 0;
         foreach ($custom->result_array() as $row) : ?>
	    				<tr class="cursor-pointer zoom-in" onclick="window.location='<?= site_url('Kasir/cari/'.$row['trans_kode'])?>'">
	    					<td class="text-center border-b"><?= ++$no?></td>
	    			              <td class="text-center border-b">
	    			              	<?= $row['cos_kode']?>
	    			              </td>
	    			              <td class="border-b">
	    			              	<div class="font-medium whitespace-no-wrap">
	    			              		<?= $row['cos_nama']?>
	    			              	</div>
	    			                  <div class="text-gray-600 text-xs whitespace-no-wrap">
	    			                  	STATUS : <?= $row['trans_status']?>
	    			                  </div>
	    			              </td>
	    			              <td class="text-center border-b"><?= isset($row['cos_model']) ? $row['cos_model'] : '-' ?></td>
	    			              <td class="text-center border-b"><?= isset($row['cos_status']) ? $row['cos_status'] : '-' ?></td>
	    			              <td class="border-b">
	    			              	<div class="font-medium whitespace-no-wrap">
	    			              		<?php echo date('d-m-Y',strtotime($row['cos_tanggal'])) ?>
	    			              	</div>
	    			                  <div class="text-gray-600 text-xs whitespace-no-wrap">
	    			                  	JAM : <?= $row['cos_jam']?>
	    			                  </div>
	    			              </td>
	    			          </tr>
	    				
	    			<?php endforeach; ?>    			
	    		</tbody>
            	</table>
            	<div class="mt-5" id="pagination-container-pembayaran">
            		<?php echo $pagination; ?>
            	</div>
            </div>
        </div>
        <!-- END: Item List -->
        </div>
    </main>
</div>

<!-- Overlay for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

<script>
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

    // Pembayaran Pagination
    var currentPagePembayaran = 1;
    var currentSearchPembayaran = '';
    var currentFilterPembayaran = '<?= isset($filter) ? $filter : '' ?>';

    // Wait for jQuery to be loaded
    function initSearchPembayaran() {
        if (typeof $ === 'undefined') {
            setTimeout(initSearchPembayaran, 100);
            return;
        }

        $('#search-input-pembayaran').on('keyup', function() {
            currentSearchPembayaran = $(this).val();
            currentPagePembayaran = 1;
            loadDataPembayaran();
        });

        // Initialize with current search if any
        currentSearchPembayaran = $('#search-input-pembayaran').val();
    }

    window.addEventListener('load', initSearchPembayaran);

    function loadPagePembayaran(page) {
        currentPagePembayaran = page;
        loadDataPembayaran();
    }

    function loadDataPembayaran() {
        $.ajax({
            url: '<?= site_url('Kasir/pembayaran_ajax') ?>',
            type: 'POST',
            data: {
                search: currentSearchPembayaran,
                page: currentPagePembayaran,
                filter: currentFilterPembayaran
            },
            dataType: 'json',
            success: function(response) {
                $('#table-body-pembayaran').html(response.table);
                $('#pagination-container-pembayaran').html(response.pagination);
                $('#pagination-container-top-pembayaran').html(response.pagination);
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
</script>

<?php $this->load->view('Template/footer'); ?>