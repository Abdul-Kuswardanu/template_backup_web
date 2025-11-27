<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
	}

	public function index()
	{
		// Cek apakah sudah login
		if (!$this->session->userdata('user_id')) {
			redirect('auth/login');
		}

		$data['title'] = 'Data Backup - Home';
		$data['user_id'] = $this->session->userdata('user_id');
		
		// Daftar folder yang akan ditampilkan (hanya folder yang ada)
		$data['folders'] = array();
		
		// Cek dan tambahkan folder yang ada
		$possible_folders = array(
			array('path' => 'upload_1', 'name' => 'upload_1', 'class' => 'folder1'),
			array('path' => 'upload_2', 'name' => 'upload_2', 'class' => 'folder2'),
		);
		
		foreach ($possible_folders as $folder) {
			$full_path = FCPATH . $folder['path'];
			if (is_dir($full_path)) {
				$data['folders'][] = $folder;
			}
		}

		$this->load->view('home/index', $data);
	}

	public function download()
	{
		// Cek apakah sudah login
		if (!$this->session->userdata('user_id')) {
			redirect('auth/login');
		}

		$selected_files = $this->input->post('selected_files');

		if (empty($selected_files) || !is_array($selected_files)) {
			$this->session->set_flashdata('error', 'Pilih file terlebih dahulu');
			redirect('home');
		}

		// Validasi dan buat ZIP
		$directoryPath = FCPATH . 'download_file/';
		if (!is_dir($directoryPath)) {
			mkdir($directoryPath, 0755, true);
		}

		$zipFileName = 'file_download_' . date('YmdHis') . '.zip';
		$zipFilePath = $directoryPath . $zipFileName;

		$zip = new ZipArchive();
		if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
			foreach ($selected_files as $selectedFile) {
				// Validasi path untuk keamanan
				$filePath = realpath($selectedFile);
				if ($filePath && file_exists($filePath) && is_file($filePath)) {
					$selectedfile2 = str_replace('../', '', $selectedFile);
					$zip->addFile($filePath, $selectedfile2);
				}
			}
			$zip->close();

			// Set headers untuk download
			header('Content-Type: application/zip');
			header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, public');
			header('Pragma: public');
			header('Content-Length: ' . filesize($zipFilePath));
			ob_clean();
			flush();
			readfile($zipFilePath);
			unlink($zipFilePath);
			exit();
		} else {
			$this->session->set_flashdata('error', 'Gagal membuat file ZIP');
			redirect('home');
		}
	}

	public function upload()
	{
		// Cek apakah sudah login
		if (!$this->session->userdata('user_id')) {
			redirect('auth/login');
		}

		$response = array('success' => false, 'message' => '');

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES)) {
			// Tentukan folder tujuan upload
			$target_folder = $this->input->post('target_folder');
			if (empty($target_folder)) {
				$target_folder = 'upload_1';
			}

			// Validasi folder
			$allowed_folders = array('upload_1', 'upload_2');
			if (!in_array($target_folder, $allowed_folders)) {
				$response['message'] = 'Folder tujuan tidak valid';
				echo json_encode($response);
				return;
			}

			// Path lengkap ke folder upload
			$target_folder = FCPATH . $target_folder;

			// Pastikan folder ada
			if (!is_dir($target_folder)) {
				mkdir($target_folder, 0755, true);
			}

			$uploaded_files = array();
			$errors = array();

			// Proses setiap file
			foreach ($_FILES as $key => $file) {
				if ($file['error'] === UPLOAD_ERR_OK) {
					// Validasi ukuran file (max 50MB)
					if ($file['size'] > 50 * 1024 * 1024) {
						$errors[] = $file['name'] . ' terlalu besar (max 50MB)';
						continue;
					}

					// Validasi ekstensi file
					$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar');
					$file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
					
					if (!in_array($file_extension, $allowed_extensions)) {
						$errors[] = $file['name'] . ' - ekstensi tidak diizinkan';
						continue;
					}

					// Generate nama file unik
					$file_name = $file['name'];
					$file_path = $target_folder . '/' . $file_name;

					// Jika file sudah ada, tambahkan timestamp
					if (file_exists($file_path)) {
						$file_name = pathinfo($file['name'], PATHINFO_FILENAME) . '_' . date('YmdHis') . '.' . $file_extension;
						$file_path = $target_folder . '/' . $file_name;
					}

					// Upload file
					if (move_uploaded_file($file['tmp_name'], $file_path)) {
						$uploaded_files[] = $file_name;
					} else {
						$errors[] = $file['name'] . ' - gagal diupload';
					}
				} else {
					$errors[] = $file['name'] . ' - error: ' . $this->getUploadErrorMessage($file['error']);
				}
			}

			if (!empty($uploaded_files)) {
				$response['success'] = true;
				$response['message'] = count($uploaded_files) . ' file berhasil diupload';
				$response['files'] = $uploaded_files;
			}

			if (!empty($errors)) {
				$response['errors'] = $errors;
				if (!$response['success']) {
					$response['message'] = implode(', ', $errors);
				}
			}
		} else {
			$response['message'] = 'Tidak ada file yang diupload';
		}

		header('Content-Type: application/json');
		echo json_encode($response);
	}

	private function getUploadErrorMessage($error_code)
	{
		switch ($error_code) {
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				return 'File terlalu besar';
			case UPLOAD_ERR_PARTIAL:
				return 'File hanya terupload sebagian';
			case UPLOAD_ERR_NO_FILE:
				return 'Tidak ada file yang diupload';
			case UPLOAD_ERR_NO_TMP_DIR:
				return 'Folder temporary tidak ditemukan';
			case UPLOAD_ERR_CANT_WRITE:
				return 'Gagal menulis file ke disk';
			case UPLOAD_ERR_EXTENSION:
				return 'Upload dihentikan oleh extension';
			default:
				return 'Error tidak diketahui';
		}
	}
}

