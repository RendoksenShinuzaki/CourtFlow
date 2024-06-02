<?php
session_start();
include('../../connection/config.php');

$loggedInEmployeeId = $_SESSION['employeeId'] ?? '';

$PaoResult = "SELECT * FROM v1_pao_submit_case ORDER BY id DESC";
$PaoResultSql = $conn->query($PaoResult);

$FiscalSql = "SELECT * FROM v2_fiscal_submit_case ORDER BY id DESC";
$FiscalResult = $conn->query($FiscalSql);

$OccAssignmentSql = "SELECT * FROM v3_occ_case_assignment ORDER BY id DESC";
$occAssignmentResult = $conn->query($OccAssignmentSql);

// $OccAssignmentSql = "SELECT * FROM v3_occ_case_assignment";
// $occAssignmentResult = $conn->query($OccAssignmentSql);

$warrantSql = "SELECT * FROM v4_rtc_submit_warrant WHERE FromEmployee = '$loggedInEmployeeId' ORDER BY DateSubmitted DESC";
$warrantSqlResult = $conn->query($warrantSql);

$loggedInRole = $_SESSION['role'] ?? '';
$stmt = $conn->prepare("SELECT * FROM roles WHERE Role=?");
$stmt->bind_param("s", $loggedInRole);
$stmt->execute();
$result = $stmt->get_result();
$num = $result->num_rows;
$row = $result->fetch_assoc();

$CaseHistory = $row['CaseHistory'];
$SubmitCase = $row['SubmitCase'];
$SubmitWarrant = $row['SubmitWarrant'];
$RetrievedDocuments = $row['RetrievedDocuments'];
$MatrixPenalties = $row['MatrixPenalties'];
$SentCases = $row['SentCases'];
$Inbox = $row['Inbox'];
$CaseAssignment = $row['CaseAssignment'];
$RetrievedCases = $row['RetrievedCases'];
$WarrantArrest = $row['WarrantArrest'];
$Archived = $row['Archived'];
$ScheduledHearing = $row['ScheduledHearing'];
$Documents = $row['Documents'];
$CaseList = $row['CaseList'];
$QRScanner = $row['QRScanner'];

if ($_SESSION['active'] == 0) {
    header('Location: userBanned.php');
    exit;
}
if (!isset($_SESSION['userLoggedIn']) || !$_SESSION['userLoggedIn']) {
    // Redirect to the login page
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../../dist/output.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <title>Sent Cases</title>
</head>

<body class="bg-gray-200">
    <div class="hidden lg:block navbar-menu relative z-50">
        <div class="navbar-backdrop fixed lg:hidden inset-0 bg-white opacity-10"></div>
        <nav class="fixed top-0 left-0 bottom-0 flex flex-col w-3/4 lg:w-80 sm:max-w-xs pt-6 pb-8 bg-white overflow-y-auto border-l border-gray-400 shadow-2xl">
            <div class="flex w-full items-center px-6 pb-6 mb-6 lg:border-b border-gray-300">
                <a class="text-xl text-white font-semibold" href="#">
                    <img class="h-8" src="../../images/CFLogo.png" alt="" width="auto">
                </a>
                <span class="text-2xl text-gray-800 ml-2 inline-block uppercase font-bold">CourtFlow</span>
            </div>
            <div class="px-4 pb-6">
                <?php if (!empty($loggedInRole)) : ?>
                    <h3 class="mb-2 text-xs uppercase text-gray-500 font-medium">User Dashboard <?php echo $loggedInEmployeeId; ?>
                    </h3>
                <?php endif; ?>
                <ul class="text-sm font-medium">
                    <li>
                        <a class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white transition duration-300 border-gray-500 rounded" href="userDashboard.php">
                            <span class="inline-block mr-3">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </span>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="<?php echo ($CaseHistory === 0) ? 'hidden' : ''; ?>">
                        <a class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white transition duration-300 border-gray-500 rounded" href="userCaseHistory.php">
                            <span class="inline-block mr-3">
                                <svg class="w-5 h-5 hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 6v14c0 1.1 .9 2 2 2h12c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2H6c-1.1 0-2 .9-2 2zm0 0h16M12 4v4m0 6h.01m-.01 2H12m0 6v-2"></path>
                                        </svg>
                            </span>
                            <span>Case History</span>
                        </a>
                    </li>
                    <li class="<?php echo ($SubmitCase === 0) ? 'hidden' : ''; ?>">
                        <a class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white transition duration-300 border-gray-500 rounded" href="userSubmitCase.php">
                            <span class="inline-block mr-3">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12L3 22l4-10-4-10 18 10z">
                                    </path>
                                    </svg>
                            </span>
                            <span>Submit Case</span>
                        </a>
                    </li>
                    <li class="<?php echo ($SubmitWarrant === 0) ? 'hidden' : ''; ?>">
                        <a class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white transition duration-300 border-gray-500 rounded" href="userSubmitWarrant.php">
                            <span class="inline-block mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 0 1 9 9v.375M10.125 2.25A3.375 3.375 0 0 1 13.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 0 1 3.375 3.375M9 15l2.25 2.25L15 12" />
                                        </svg>
                            </span>
                            <span>Submit Warrant</span>
                        </a>
                    </li>
                    <li class="<?php echo ($RetrievedDocuments === 0) ? 'hidden' : ''; ?>">
                        <a href="userRetrievedDocuments.php" class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white transition duration-300 border-gray-500 rounded">
                            <span class="inline-block mr-3">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejo="round">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"></path>
                                        <path d="M14 2v6h6"></path>
                                        <path d="M16 13l-4 4-4-4m4-5v9"></path>
                                        </svg>
                            </span>
                            <span>Retrieved Documents</span>
                        </a>
                    </li>
                    <li class="<?php echo ($MatrixPenalties === 0) ? 'hidden' : ''; ?>">
                        <a class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white transition duration-300 border-gray-500 rounded" href="userMatrixPenalties.php">
                            <span class="inline-block mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                                    </svg>
                            </span>
                            <span>Matrix Penalties</span>
                        </a>
                    </li>
                    <li class="<?php echo ($SentCases === 0) ? 'hidden' : ''; ?>">
                        <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 bg-gray-900 rounded">
                            <span class="inline-block mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                    </svg>
                            </span>
                            <span>Sent Cases</span>
                        </a>
                    </li>
                    <li class="<?php echo ($CaseAssignment === 0) ? 'hidden' : ''; ?>">
                        <a class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white transition duration-300 border-gray-500 rounded" href="userCaseAssignment.php">
                            <span class="inline-block mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                            </span>
                            <span>Case Assignment</span>
                        </a>
                    </li>
                    <li class="<?php echo ($RetrievedCases === 0) ? 'hidden' : ''; ?>">
                        <a class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white transition duration-300 border-gray-500 rounded" href="#">
                            <span class="inline-block mr-3">
                                <svg class="w-5 h-5" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.8802 1.66663H4.2135C3.55068 1.66735 2.91522 1.93097 2.44653 2.39966C1.97785 2.86834 1.71422 3.50381 1.7135 4.16663V15.8333C1.71422 16.4961 1.97785 17.1316 2.44653 17.6003C2.91522 18.0689 3.55068 18.3326 4.2135 18.3333H15.8802C16.543 18.3326 17.1785 18.0689 17.6471 17.6003C18.1158 17.1316 18.3794 16.4961 18.3802 15.8333V4.16663C18.3794 3.50381 18.1158 2.86834 17.6471 2.39966C17.1785 1.93097 16.543 1.66735 15.8802 1.66663ZM4.2135 3.33329H15.8802C16.1011 3.33351 16.3129 3.42138 16.4692 3.57761C16.6254 3.73385 16.7133 3.94568 16.7135 4.16663V10.8333H14.6595C14.385 10.8331 14.1148 10.9007 13.8729 11.0302C13.6309 11.1597 13.4248 11.347 13.2728 11.5755L12.1009 13.3333H7.9928L6.82093 11.5755C6.6689 11.347 6.46273 11.1597 6.22079 11.0302C5.97884 10.9007 5.70863 10.8331 5.43421 10.8333H3.38017V4.16663C3.38039 3.94568 3.46826 3.73385 3.62449 3.57761C3.78072 3.42138 3.99255 3.33351 4.2135 3.33329ZM15.8802 16.6666H4.2135C3.99255 16.6664 3.78072 16.5785 3.62449 16.4223C3.46826 16.2661 3.38039 16.0542 3.38017 15.8333V12.5H5.43421L6.60608 14.2578C6.75811 14.4862 6.96428 14.6736 7.20622 14.803C7.44817 14.9325 7.71838 15.0002 7.9928 15H12.1009C12.3753 15.0002 12.6455 14.9325 12.8875 14.803C13.1294 14.6736 13.3356 14.4862 13.4876 14.2578L14.6595 12.5H16.7135V15.8333C16.7133 16.0542 16.6254 16.2661 16.4692 16.4223C16.3129 16.5785 16.1011 16.6664 15.8802 16.6666Z" fill="currentColor"></path>
                                </svg>
                            </span>
                            <span>Retrieved Cases</span>
                        </a>
                    </li>
                    <li class="<?php echo ($WarrantArrest === 0) ? 'hidden' : ''; ?>">
                        <a href="userWarrant.php" class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white transition duration-300 border-gray-500 rounded" >
                            <span class="inline-block mr-3">
                                <svg class="w-5 h-5" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.8802 1.66663H4.2135C3.55068 1.66735 2.91522 1.93097 2.44653 2.39966C1.97785 2.86834 1.71422 3.50381 1.7135 4.16663V15.8333C1.71422 16.4961 1.97785 17.1316 2.44653 17.6003C2.91522 18.0689 3.55068 18.3326 4.2135 18.3333H15.8802C16.543 18.3326 17.1785 18.0689 17.6471 17.6003C18.1158 17.1316 18.3794 16.4961 18.3802 15.8333V4.16663C18.3794 3.50381 18.1158 2.86834 17.6471 2.39966C17.1785 1.93097 16.543 1.66735 15.8802 1.66663ZM4.2135 3.33329H15.8802C16.1011 3.33351 16.3129 3.42138 16.4692 3.57761C16.6254 3.73385 16.7133 3.94568 16.7135 4.16663V10.8333H14.6595C14.385 10.8331 14.1148 10.9007 13.8729 11.0302C13.6309 11.1597 13.4248 11.347 13.2728 11.5755L12.1009 13.3333H7.9928L6.82093 11.5755C6.6689 11.347 6.46273 11.1597 6.22079 11.0302C5.97884 10.9007 5.70863 10.8331 5.43421 10.8333H3.38017V4.16663C3.38039 3.94568 3.46826 3.73385 3.62449 3.57761C3.78072 3.42138 3.99255 3.33351 4.2135 3.33329ZM15.8802 16.6666H4.2135C3.99255 16.6664 3.78072 16.5785 3.62449 16.4223C3.46826 16.2661 3.38039 16.0542 3.38017 15.8333V12.5H5.43421L6.60608 14.2578C6.75811 14.4862 6.96428 14.6736 7.20622 14.803C7.44817 14.9325 7.71838 15.0002 7.9928 15H12.1009C12.3753 15.0002 12.6455 14.9325 12.8875 14.803C13.1294 14.6736 13.3356 14.4862 13.4876 14.2578L14.6595 12.5H16.7135V15.8333C16.7133 16.0542 16.6254 16.2661 16.4692 16.4223C16.3129 16.5785 16.1011 16.6664 15.8802 16.6666Z" fill="currentColor"></path>
                                </svg>
                            </span>
                            <span>Warrant of Arrest</span>
                        </a>
                    </li>
                    <li class="<?php echo ($Archived === 0) ? 'hidden' : ''; ?>">
                        <a href="userArchived.php" class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white transition duration-300 border-gray-500 rounded">
                            <span class="inline-block mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        </svg>
                            </span>
                            <span>Archived</span>
                        </a>
                    </li>
                    <li class="<?php echo ($ScheduledHearing === 0) ? 'hidden' : ''; ?>">
                        <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="#">
                            <span class="inline-block mr-3">
                                <svg class="w-5 h-5" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.8802 1.66663H4.2135C3.55068 1.66735 2.91522 1.93097 2.44653 2.39966C1.97785 2.86834 1.71422 3.50381 1.7135 4.16663V15.8333C1.71422 16.4961 1.97785 17.1316 2.44653 17.6003C2.91522 18.0689 3.55068 18.3326 4.2135 18.3333H15.8802C16.543 18.3326 17.1785 18.0689 17.6471 17.6003C18.1158 17.1316 18.3794 16.4961 18.3802 15.8333V4.16663C18.3794 3.50381 18.1158 2.86834 17.6471 2.39966C17.1785 1.93097 16.543 1.66735 15.8802 1.66663ZM4.2135 3.33329H15.8802C16.1011 3.33351 16.3129 3.42138 16.4692 3.57761C16.6254 3.73385 16.7133 3.94568 16.7135 4.16663V10.8333H14.6595C14.385 10.8331 14.1148 10.9007 13.8729 11.0302C13.6309 11.1597 13.4248 11.347 13.2728 11.5755L12.1009 13.3333H7.9928L6.82093 11.5755C6.6689 11.347 6.46273 11.1597 6.22079 11.0302C5.97884 10.9007 5.70863 10.8331 5.43421 10.8333H3.38017V4.16663C3.38039 3.94568 3.46826 3.73385 3.62449 3.57761C3.78072 3.42138 3.99255 3.33351 4.2135 3.33329ZM15.8802 16.6666H4.2135C3.99255 16.6664 3.78072 16.5785 3.62449 16.4223C3.46826 16.2661 3.38039 16.0542 3.38017 15.8333V12.5H5.43421L6.60608 14.2578C6.75811 14.4862 6.96428 14.6736 7.20622 14.803C7.44817 14.9325 7.71838 15.0002 7.9928 15H12.1009C12.3753 15.0002 12.6455 14.9325 12.8875 14.803C13.1294 14.6736 13.3356 14.4862 13.4876 14.2578L14.6595 12.5H16.7135V15.8333C16.7133 16.0542 16.6254 16.2661 16.4692 16.4223C16.3129 16.5785 16.1011 16.6664 15.8802 16.6666Z" fill="currentColor"></path>
                                </svg>
                            </span>
                            <span>Scheduled Hearing</span>
                        </a>
                    </li>
                    <li class="<?php echo ($Documents === 0) ? 'hidden' : ''; ?>">
                        <a class="flex items-center pl-3 py-3 pr-4 text-gray-50 hover:bg-gray-900 rounded" href="#">
                            <span class="inline-block mr-3">
                                <svg class="w-5 h-5" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.8802 1.66663H4.2135C3.55068 1.66735 2.91522 1.93097 2.44653 2.39966C1.97785 2.86834 1.71422 3.50381 1.7135 4.16663V15.8333C1.71422 16.4961 1.97785 17.1316 2.44653 17.6003C2.91522 18.0689 3.55068 18.3326 4.2135 18.3333H15.8802C16.543 18.3326 17.1785 18.0689 17.6471 17.6003C18.1158 17.1316 18.3794 16.4961 18.3802 15.8333V4.16663C18.3794 3.50381 18.1158 2.86834 17.6471 2.39966C17.1785 1.93097 16.543 1.66735 15.8802 1.66663ZM4.2135 3.33329H15.8802C16.1011 3.33351 16.3129 3.42138 16.4692 3.57761C16.6254 3.73385 16.7133 3.94568 16.7135 4.16663V10.8333H14.6595C14.385 10.8331 14.1148 10.9007 13.8729 11.0302C13.6309 11.1597 13.4248 11.347 13.2728 11.5755L12.1009 13.3333H7.9928L6.82093 11.5755C6.6689 11.347 6.46273 11.1597 6.22079 11.0302C5.97884 10.9007 5.70863 10.8331 5.43421 10.8333H3.38017V4.16663C3.38039 3.94568 3.46826 3.73385 3.62449 3.57761C3.78072 3.42138 3.99255 3.33351 4.2135 3.33329ZM15.8802 16.6666H4.2135C3.99255 16.6664 3.78072 16.5785 3.62449 16.4223C3.46826 16.2661 3.38039 16.0542 3.38017 15.8333V12.5H5.43421L6.60608 14.2578C6.75811 14.4862 6.96428 14.6736 7.20622 14.803C7.44817 14.9325 7.71838 15.0002 7.9928 15H12.1009C12.3753 15.0002 12.6455 14.9325 12.8875 14.803C13.1294 14.6736 13.3356 14.4862 13.4876 14.2578L14.6595 12.5H16.7135V15.8333C16.7133 16.0542 16.6254 16.2661 16.4692 16.4223C16.3129 16.5785 16.1011 16.6664 15.8802 16.6666Z" fill="currentColor"></path>
                                </svg>
                            </span>
                            <span>Documents</span>
                        </a>
                    </li>
                    <li class="<?php echo ($CaseList === 0) ? 'hidden' : ''; ?>">
                        <a class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white transition duration-300 border-gray-500 rounded" href="userCaseList.php">
                            <span class="inline-block mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                    </svg>
                            </span>
                            <span>Case List</span>
                        </a>
                    </li>
                    <li class="<?php echo ($Inbox === 0) ? 'hidden' : ''; ?>">
                        <a class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white transition duration-300 border-gray-500 rounded" href="userInbox.php">
                            <span class="inline-block mr-3">
                                <svg class="w-5 h-5" viewbox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.9831 6.64169C18.9047 6.545 18.8056 6.46712 18.6931 6.41376C18.5806 6.36041 18.4576 6.33293 18.3331 6.33335H16.6665V5.50002C16.6665 4.83698 16.4031 4.20109 15.9342 3.73225C15.4654 3.26341 14.8295 3.00002 14.1665 3.00002H8.93313L8.66646 2.16669C8.49359 1.67771 8.17292 1.2546 7.74888 0.955986C7.32484 0.657367 6.81843 0.498019 6.2998 0.500019H3.33313C2.67009 0.500019 2.0342 0.763411 1.56536 1.23225C1.09652 1.70109 0.83313 2.33698 0.83313 3.00002V13C0.83313 13.6631 1.09652 14.2989 1.56536 14.7678C2.0342 15.2366 2.67009 15.5 3.33313 15.5H15.3331C15.9008 15.4984 16.451 15.3036 16.8933 14.9476C17.3355 14.5917 17.6435 14.0959 17.7665 13.5417L19.1665 7.35002C19.1918 7.22578 19.1885 7.0974 19.1567 6.97466C19.1249 6.85191 19.0656 6.73803 18.9831 6.64169ZM4.4748 13.1834C4.43246 13.3713 4.32629 13.5388 4.17435 13.6574C4.02241 13.7759 3.8341 13.8381 3.64146 13.8334H3.33313C3.11212 13.8334 2.90015 13.7456 2.74387 13.5893C2.58759 13.433 2.4998 13.221 2.4998 13V3.00002C2.4998 2.779 2.58759 2.56704 2.74387 2.41076C2.90015 2.25448 3.11212 2.16669 3.33313 2.16669H6.2998C6.48152 2.1572 6.66135 2.20746 6.81183 2.30978C6.9623 2.4121 7.07515 2.56087 7.13313 2.73335L7.58313 4.10002C7.6366 4.25897 7.7368 4.39809 7.8706 4.49919C8.00441 4.60029 8.16561 4.65867 8.33313 4.66669H14.1665C14.3875 4.66669 14.5994 4.75448 14.7557 4.91076C14.912 5.06704 14.9998 5.27901 14.9998 5.50002V6.33335H6.66646C6.47383 6.32864 6.28551 6.39084 6.13358 6.50935C5.98164 6.62786 5.87546 6.79537 5.83313 6.98335L4.4748 13.1834ZM16.1415 13.1834C16.0991 13.3713 15.993 13.5388 15.841 13.6574C15.6891 13.7759 15.5008 13.8381 15.3081 13.8334H6.00813C6.05117 13.7405 6.08198 13.6425 6.0998 13.5417L7.33313 8.00002H17.3331L16.1415 13.1834Z" fill="currentColor"></path>
                                </svg>
                            </span>
                            <span>Inbox</span>
                        </a>
                    </li>
                    <li class="<?php echo ($QRScanner === 0) ? 'hidden' : ''; ?>">
                        <a class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white transition duration-300 border-gray-500 rounded" href="userQRScanner.php">
                            <span class="inline-block mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
                                    </svg>
                            </span>
                            <span>QR Scanner</span>
                        </a>
                    </li>
                </ul>
                <div class="pt-8">
                    <a class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white transition duration-300 border-gray-500 rounded" href="#">
                        <span class="inline-block mr-4">
                            <svg class="w-5 h-5" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.7666 7.9583L16.1916 7.4333L16.9333 5.94996C17.0085 5.7947 17.0336 5.61993 17.0053 5.44977C16.9769 5.27961 16.8964 5.12245 16.775 4.99996L15 3.22496C14.8768 3.1017 14.7182 3.02013 14.5463 2.99173C14.3743 2.96333 14.1979 2.98953 14.0416 3.06663L12.5583 3.8083L12.0333 2.2333C11.9778 2.06912 11.8726 1.92632 11.7322 1.82475C11.5918 1.72319 11.4232 1.66792 11.25 1.66663H8.74996C8.57526 1.66618 8.40483 1.72064 8.26277 1.82233C8.12071 1.92402 8.0142 2.06778 7.9583 2.2333L7.4333 3.8083L5.94996 3.06663C5.7947 2.99145 5.61993 2.9663 5.44977 2.99466C5.27961 3.02302 5.12245 3.10349 4.99996 3.22496L3.22496 4.99996C3.1017 5.1231 3.02013 5.28177 2.99173 5.45368C2.96333 5.62558 2.98953 5.80205 3.06663 5.9583L3.8083 7.44163L2.2333 7.96663C2.06912 8.02208 1.92632 8.12732 1.82475 8.26772C1.72319 8.40812 1.66792 8.57668 1.66663 8.74996V11.25C1.66618 11.4247 1.72064 11.5951 1.82233 11.7372C1.92402 11.8792 2.06778 11.9857 2.2333 12.0416L3.8083 12.5666L3.06663 14.05C2.99145 14.2052 2.9663 14.38 2.99466 14.5502C3.02302 14.7203 3.10349 14.8775 3.22496 15L4.99996 16.775C5.1231 16.8982 5.28177 16.9798 5.45368 17.0082C5.62558 17.0366 5.80205 17.0104 5.9583 16.9333L7.44163 16.1916L7.96663 17.7666C8.02253 17.9321 8.12904 18.0759 8.2711 18.1776C8.41317 18.2793 8.58359 18.3337 8.7583 18.3333H11.2583C11.433 18.3337 11.6034 18.2793 11.7455 18.1776C11.8875 18.0759 11.9941 17.9321 12.05 17.7666L12.575 16.1916L14.0583 16.9333C14.2126 17.0066 14.3856 17.0307 14.5541 17.0024C14.7225 16.9741 14.8781 16.8947 15 16.775L16.775 15C16.8982 14.8768 16.9798 14.7182 17.0082 14.5463C17.0366 14.3743 17.0104 14.1979 16.9333 14.0416L16.1916 12.5583L17.7666 12.0333C17.9308 11.9778 18.0736 11.8726 18.1752 11.7322C18.2767 11.5918 18.332 11.4232 18.3333 11.25V8.74996C18.3337 8.57526 18.2793 8.40483 18.1776 8.26277C18.0759 8.12071 17.9321 8.0142 17.7666 7.9583ZM16.6666 10.65L15.6666 10.9833C15.4367 11.0579 15.2257 11.1816 15.0483 11.3459C14.871 11.5102 14.7315 11.711 14.6395 11.9346C14.5475 12.1582 14.5053 12.3991 14.5158 12.6406C14.5262 12.8821 14.5891 13.1185 14.7 13.3333L15.175 14.2833L14.2583 15.2L13.3333 14.7C13.1196 14.5935 12.8855 14.5342 12.6469 14.526C12.4083 14.5179 12.1707 14.5611 11.9502 14.6528C11.7298 14.7445 11.5316 14.8824 11.3691 15.0573C11.2066 15.2322 11.0835 15.44 11.0083 15.6666L10.675 16.6666H9.34996L9.01663 15.6666C8.94204 15.4367 8.81832 15.2257 8.65404 15.0483C8.48977 14.871 8.28888 14.7315 8.06531 14.6395C7.84174 14.5475 7.60084 14.5053 7.35932 14.5158C7.11779 14.5262 6.88143 14.5891 6.66663 14.7L5.71663 15.175L4.79996 14.2583L5.29996 13.3333C5.41087 13.1185 5.47373 12.8821 5.48417 12.6406C5.49461 12.3991 5.45238 12.1582 5.36041 11.9346C5.26845 11.711 5.12894 11.5102 4.95158 11.3459C4.77422 11.1816 4.56325 11.0579 4.3333 10.9833L3.3333 10.65V9.34996L4.3333 9.01663C4.56325 8.94204 4.77422 8.81832 4.95158 8.65404C5.12894 8.48977 5.26845 8.28888 5.36041 8.06531C5.45238 7.84174 5.49461 7.60084 5.48417 7.35932C5.47373 7.11779 5.41087 6.88143 5.29996 6.66663L4.82496 5.74163L5.74163 4.82496L6.66663 5.29996C6.88143 5.41087 7.11779 5.47373 7.35932 5.48417C7.60084 5.49461 7.84174 5.45238 8.06531 5.36041C8.28888 5.26845 8.48977 5.12894 8.65404 4.95158C8.81832 4.77422 8.94204 4.56325 9.01663 4.3333L9.34996 3.3333H10.65L10.9833 4.3333C11.0579 4.56325 11.1816 4.77422 11.3459 4.95158C11.5102 5.12894 11.711 5.26845 11.9346 5.36041C12.1582 5.45238 12.3991 5.49461 12.6406 5.48417C12.8821 5.47373 13.1185 5.41087 13.3333 5.29996L14.2833 4.82496L15.2 5.74163L14.7 6.66663C14.5935 6.88033 14.5342 7.11442 14.526 7.35304C14.5179 7.59165 14.5611 7.82924 14.6528 8.0497C14.7445 8.27016 14.8824 8.46835 15.0573 8.63086C15.2322 8.79337 15.44 8.9164 15.6666 8.99163L16.6666 9.32496V10.65ZM9.99996 6.66663C9.34069 6.66663 8.69623 6.86213 8.14806 7.2284C7.5999 7.59467 7.17266 8.11526 6.92036 8.72435C6.66807 9.33344 6.60206 10.0037 6.73068 10.6503C6.8593 11.2969 7.17676 11.8908 7.64294 12.357C8.10911 12.8232 8.70306 13.1406 9.34966 13.2692C9.99626 13.3979 10.6665 13.3319 11.2756 13.0796C11.8847 12.8273 12.4053 12.4 12.7715 11.8519C13.1378 11.3037 13.3333 10.6592 13.3333 9.99996C13.3333 9.11591 12.9821 8.26806 12.357 7.64294C11.7319 7.01782 10.884 6.66663 9.99996 6.66663ZM9.99996 11.6666C9.67033 11.6666 9.34809 11.5689 9.07401 11.3857C8.79993 11.2026 8.58631 10.9423 8.46016 10.6378C8.33402 10.3332 8.30101 9.99811 8.36532 9.67481C8.42963 9.35151 8.58836 9.05454 8.82145 8.82145C9.05454 8.58836 9.35151 8.42963 9.67481 8.36532C9.99811 8.30101 10.3332 8.33402 10.6378 8.46016C10.9423 8.58631 11.2026 8.79993 11.3857 9.07401C11.5689 9.34809 11.6666 9.67033 11.6666 9.99996C11.6666 10.442 11.491 10.8659 11.1785 11.1785C10.8659 11.491 10.442 11.6666 9.99996 11.6666Z" fill="currentColor"></path>
                            </svg>
                        </span>
                        <span class="font-semibold">Settings</span>
                    </a>
                    <form action="../../controller/logout.php" method="post">
                        <a class="flex items-center pl-3 py-3 pr-2 text-gray-500 hover:bg-gray-700 hover:text-white transition duration-300 border-gray-500 rounded">
                            <span class="inline-block mr-4">
                                <svg class="w-5 h-5" viewbox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0.333618 8.99996C0.333618 9.22097 0.421416 9.43293 0.577696 9.58922C0.733976 9.7455 0.945938 9.83329 1.16695 9.83329H7.49195L5.57528 11.7416C5.49718 11.8191 5.43518 11.9113 5.39287 12.0128C5.35057 12.1144 5.32879 12.2233 5.32879 12.3333C5.32879 12.4433 5.35057 12.5522 5.39287 12.6538C5.43518 12.7553 5.49718 12.8475 5.57528 12.925C5.65275 13.0031 5.74492 13.0651 5.84647 13.1074C5.94802 13.1497 6.05694 13.1715 6.16695 13.1715C6.27696 13.1715 6.38588 13.1497 6.48743 13.1074C6.58898 13.0651 6.68115 13.0031 6.75862 12.925L10.0919 9.59163C10.1678 9.51237 10.2273 9.41892 10.2669 9.31663C10.3503 9.11374 10.3503 8.88618 10.2669 8.68329C10.2273 8.581 10.1678 8.48755 10.0919 8.40829L6.75862 5.07496C6.68092 4.99726 6.58868 4.93563 6.48716 4.89358C6.38564 4.85153 6.27683 4.82988 6.16695 4.82988C6.05707 4.82988 5.94826 4.85153 5.84674 4.89358C5.74522 4.93563 5.65298 4.99726 5.57528 5.07496C5.49759 5.15266 5.43595 5.2449 5.3939 5.34642C5.35185 5.44794 5.33021 5.55674 5.33021 5.66663C5.33021 5.77651 5.35185 5.88532 5.3939 5.98683C5.43595 6.08835 5.49759 6.18059 5.57528 6.25829L7.49195 8.16663H1.16695C0.945938 8.16663 0.733976 8.25442 0.577696 8.4107C0.421416 8.56698 0.333618 8.77895 0.333618 8.99996ZM11.1669 0.666626H2.83362C2.17058 0.666626 1.53469 0.930018 1.06585 1.39886C0.59701 1.8677 0.333618 2.50358 0.333618 3.16663V5.66663C0.333618 5.88764 0.421416 6.0996 0.577696 6.25588C0.733976 6.41216 0.945938 6.49996 1.16695 6.49996C1.38797 6.49996 1.59993 6.41216 1.75621 6.25588C1.91249 6.0996 2.00028 5.88764 2.00028 5.66663V3.16663C2.00028 2.94561 2.08808 2.73365 2.24436 2.57737C2.40064 2.42109 2.6126 2.33329 2.83362 2.33329H11.1669C11.388 2.33329 11.5999 2.42109 11.7562 2.57737C11.9125 2.73365 12.0003 2.94561 12.0003 3.16663V14.8333C12.0003 15.0543 11.9125 15.2663 11.7562 15.4225C11.5999 15.5788 11.388 15.6666 11.1669 15.6666H2.83362C2.6126 15.6666 2.40064 15.5788 2.24436 15.4225C2.08808 15.2663 2.00028 15.0543 2.00028 14.8333V12.3333C2.00028 12.1123 1.91249 11.9003 1.75621 11.744C1.59993 11.5878 1.38797 11.5 1.16695 11.5C0.945938 11.5 0.733976 11.5878 0.577696 11.744C0.421416 11.9003 0.333618 12.1123 0.333618 12.3333V14.8333C0.333618 15.4963 0.59701 16.1322 1.06585 16.6011C1.53469 17.0699 2.17058 17.3333 2.83362 17.3333H11.1669C11.83 17.3333 12.4659 17.0699 12.9347 16.6011C13.4036 16.1322 13.6669 15.4963 13.6669 14.8333V3.16663C13.6669 2.50358 13.4036 1.8677 12.9347 1.39886C12.4659 0.930018 11.83 0.666626 11.1669 0.666626Z" fill="currentColor"></path>
                                </svg>
                            </span>
                            <button type="submit" name="logout" class="font-semibold">Logout</button>
                        </a>
                    </form>
                </div>
            </div>
    </div>
    </div>
    <!--PAO SENT CASES-->
    <?php if ($loggedInRole === "PAO") : ?>

        <!--MODAL-->
        <div id="ViewQRModal" class="hidden fixed flex inset-0 z-10 items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-8 rounded-md">
                <label>Scan to Track File</label>
                <div id="qrcodePAO" class="qrcode-container mr-4"></div>
                <button type="button" name="addClose" id="addClose" class="mt-5 flex w-full justify-center rounded-md bg-gray-900 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-gray-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Close
                </button>
            </div>
        </div>

        <div class="py-20 ml-40 mr-5 pl-44">

            <div class='max-w-md h-12 '>
                <div class="flex items-center w-full py-4 rounded-lg focus-within:shadow-lg bg-white overflow-hidden">
                    <div class="grid place-items-center h-full w-12 text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input class="peer h-full w-full outline-none text-sm text-gray-700 " type="text" id="search" placeholder="Search Case Name.." />
                </div>
            </div>
            <table class="mt-5 table-auto w-full bg-white rounded">
                <thead class="border-gray-500 shadow-2xl">
                    <tr>
                        <th class="pl-6 py-6">
                            <p class="flex items-center text-xs ">From Employee</p>
                        </th>
                        <th>
                            <p class="flex items-center text-xs ">Comaplainant Name</p>
                        </th>
                        <th>
                            <p class="flex items-center text-xs ">Accused Name</p>
                        </th>
                        <th>
                            <p class="flex items-center text-xs">File Name</p>
                        </th>
                        <th>
                            <p class="flex items-center text-xs">Date Sent</p>
                        </th>
                        <th>
                            <p class="flex items-center text-xs ">Action</p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($PaoResultSql as $PaoCases) :
                    ?>
                        <tr class="text-xs bg-blue-50 border-b border-gray-100 edit-trigger">
                            <td class="hidden"><?= $PaoCases['id']; ?></td>
                            <td class="pl-6 py-6 bg-blue-100"><?= $PaoCases['FromEmployee']; ?></td>
                            <td class="pl-6 py-6 bg-blue-100"><?= $PaoCases['complainantLName']; ?></td>
                            <td class="pl-6 py-6 bg-blue-100"><?= $PaoCases['accusedLName']; ?></td>
                            <td class="pl-6 py-6 bg-blue-100"><?= $PaoCases['FileName']; ?></td>
                            <td class="pl-6 py-6 bg-blue-100"><?= $PaoCases['Date']; ?></td>
                            <td id="viewQR" class="pl-6 py-6">
                                <button type="button" class="QRButtonPAO" data-id="<?= $PaoCases['id']; ?>" onclick="generateQRCodeAndOpenModal()">View</button>
                            </td>
                        </tr>
                        <!-- <a href="userQrRedirect.php?id=<?= $PaoCases['id']; ?>"> -->
                    <?php endforeach; ?>
                </tbody>
                <tbody>
            </table>
        </div>
    <?php endif; ?>

    <!--Fiscal Sent Cases Table-->
    <?php if ($loggedInRole === "Fiscal") : ?>
        <!--FISCAL MODAL-->
        <div id="FiscalViewQRModal" class="hidden fixed flex inset-0 z-10 items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-8 rounded-md">
                <label>Scan to Track File</label>
                <div id="qrcodeFiscal" class="qrcode-container mr-4"></div>
                <button type="button" name="FiscaladdClose" id="FiscaladdClose" class="mt-5 flex w-full justify-center rounded-md bg-gray-900 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-gray-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Close
                </button>
            </div>
        </div>

        <div class="py-20 ml-40 mr-5 pl-44">
            <div class='max-w-md h-12 '>
                <div class="flex items-center w-full py-4 rounded-lg focus-within:shadow-lg bg-white overflow-hidden">
                    <div class="grid place-items-center h-full w-12 text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input class="peer h-full w-full outline-none text-sm text-gray-700 " type="text" id="search" placeholder="Search Case Name.." />
                </div>
            </div>
            <table class="mt-5 table-auto w-full bg-white rounded">
                <thead class="border-gray-500 shadow-2xl">
                    <tr>
                        <th class="pl-6 py-6">
                            <p class="flex items-center text-xs">Penalty</p>
                        </th>
                        <th>
                            <p class="flex items-center text-xs">File Name</p>
                        </th>
                        <th>
                            <p class="flex items-center text-xs">Date Submitted</p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($FiscalResult as $FiscalCases) :
                    ?>
                        <tr class="text-xs bg-blue-50 border-b border-gray-100 edit-trigger">
                            <td class="hidden"><?= $FiscalCases['id']; ?></td>
                            <td class="pl-6 py-6 bg-blue-100"><?= $FiscalCases['Penalty']; ?></td>
                            <td class="pl-6 py-6 "><?= $FiscalCases['FileName']; ?></td>
                            <td class="pl-6 py-6 bg-blue-100"><?= $FiscalCases['DateSubmitted']; ?></td>
                            <td id="viewQR" class="pl-6 py-6">
                                <button type="button" class="FiscalQRButton" data-id="<?= $FiscalCases['id']; ?>" onclick="generateQRCodeAndOpenModal()">View</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tbody>
            </table>
        </div>
    <?php endif; ?>

    <!--OCC Sent Cases Table-->
    <?php if ($loggedInRole === "OCC") : ?>
        <!--OCC MODAL-->
        <div id="OCCViewQRModal" class="hidden fixed flex inset-0 z-10 items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-8 rounded-md">
                <label>Scan to Track File</label>
                <div id="qrcodeOCC" class="qrcode-container mr-4"></div>
                <button type="button" name="OCCaddClose" id="OCCaddClose" class="mt-5 flex w-full justify-center rounded-md bg-gray-900 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-gray-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Close
                </button>
            </div>
        </div>

        <div class="py-5 ml-40 mr-5 pl-44 mt-16">
            <div class='max-w-md h-12 '>
                <div class="flex items-center w-full py-4 rounded-lg focus-within:shadow-lg bg-white overflow-hidden">
                    <div class="grid place-items-center h-full w-12 text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input class="peer h-full w-full outline-none text-sm text-gray-700 " type="text" id="search" placeholder="Search Case Name.." />
                </div>
            </div>
            <table class="mt-5 table-auto w-full bg-white rounded">
                <thead class="border-gray-500 shadow-2xl">
                    <tr>
                        <th class="pl-6 py-6">
                            <p class="flex items-center text-xs">Docket Number</p>
                        </th>
                        <th>
                            <p class="flex items-center text-xs">To Branch</p>
                        </th>
                        <th>
                            <p class="flex items-center text-xs">Docket File Name</p>
                        </th>
                        <th>
                            <p class="flex items-center text-xs">Date Submitted</p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($occAssignmentResult as $assigned) : ?>
                        <tr class="text-xs bg-blue-50 border-b border-gray-100 edit-trigger">
                            <td class="hidden"><?= $assigned['id']; ?></td>
                            <td class="pl-6 py-6 bg-blue-100"><?= $assigned['DocketNumber']; ?></td>
                            <td class="pl-6 py-6"><?= $assigned['ToBranch']; ?></td>
                            <td class="pl-6 py-6 bg-blue-100"><?= $assigned['FileName']; ?></td>
                            <td class="pl-6 py-6 "><?= $assigned['DateSubmitted']; ?></td>
                            <td id="viewQR" class="pl-6 py-6">
                                <button type="button" class="OCCQRButton"data-id="<?= $assigned['id']; ?>" onclick="generateQRCodeAndOpenModal('OCCViewQRModal', <?= $assigned['OccID']; ?>)">View</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tbody>
            </table>
        </div>
    <?php endif; ?>
    <!--RTC Sent Cases Table-->
    <?php if ($loggedInRole === "RTC") : ?>
        <div class="py-20 ml-40 mr-5 pl-44">
            <div class='max-w-md h-12 '>
                <div class="flex items-center w-full py-4 rounded-lg focus-within:shadow-lg bg-white overflow-hidden">
                    <div class="grid place-items-center h-full w-12 text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <input class="peer h-full w-full outline-none text-sm text-gray-700 " type="text" id="search" placeholder="Search Case Name.." />
                </div>
            </div>
            <table class="mt-5 table-auto w-full bg-white rounded">
                <thead class="border-gray-500 shadow-2xl">
                    <tr>
                        <th class="pl-6 py-6">
                            <p class="flex items-center text-xs">Docket Number</p>
                        </th>
                        <th>
                            <p class="flex items-center text-xs">Warrant File Name</p>
                        </th>
                        <th>
                            <p class="flex items-center text-xs">Date Submitted</p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($warrantSqlResult as $warrants) :
                    ?>
                        <tr class="text-xs bg-blue-50 border-b border-gray-100 edit-trigger">
                            <td class="hidden"><?= $warrants['ID']; ?></td>
                            <td class="pl-6 py-6 bg-blue-100"><?= $warrants['DocketNumber']; ?></td>
                            <td class="pl-6 py-6 bg-blue-100"><?= $warrants['FileName']; ?></td>
                            <td class="pl-6 py-6 "><?= $warrants['DateSubmitted']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tbody>
            </table>
        </div>
    <?php endif; ?>
</body>

</html>

<script src="../../javascript/user_userSentCases.js"></script>

<!-- Include the QRCode library -->
<script src="path/to/qrcode.min.js"></script>

<script>
    // Function to generate QR code and open modal
    function generateQRCodeAndOpenModal(modal, id) {
        const modalElement = document.getElementById(modal);
        const qrCodeContainer = modalElement.querySelector('.qrcode-container');

        // Construct the QR code URL with the ID
        const qrCodeUrl = `https://courtflow.online/CourtFlow/src/pages/user/userQrRedirect.php?id=${id}`;

        // Clear the content of the qrcode element
        qrCodeContainer.innerHTML = '';

        // Display the QR code using html5-qrcode
        const qrcode = new QRCode(qrCodeContainer, {
            text: qrCodeUrl,
            width: 128,
            height: 128,
            correctLevel: QRCode.CorrectLevel.L
        });

        // Open the modal
        modalElement.classList.remove("hidden");
    }

    // PAO
    document.addEventListener("DOMContentLoaded", function() {
        const QRButtonsPAO = document.querySelectorAll('.QRButtonPAO');

        QRButtonsPAO.forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                generateQRCodeAndOpenModal('ViewQRModal', id);
            });
        });
    });

    // Fiscal
    document.addEventListener("DOMContentLoaded", function() {
        const FiscalQRButtons = document.querySelectorAll('.FiscalQRButton');

        FiscalQRButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                generateQRCodeAndOpenModal('FiscalViewQRModal', id);
            });
        });
    });

    // OCC
    document.addEventListener("DOMContentLoaded", function() {
        const OCCQRButtons = document.querySelectorAll('.OCCQRButton');

        OCCQRButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                generateQRCodeAndOpenModal('OCCViewQRModal', id);
            });
        });
    });

    // Close Modal function
    function closeModal(modal) {
        const modalElement = document.getElementById(modal);
        modalElement.classList.add("hidden");
    }

    // Close PAO Modal
    const addClose = document.getElementById("addClose");
    addClose.addEventListener("click", () => {
        closeModal('ViewQRModal');
    });

    // Close Fiscal Modal
    const fiscalAddClose = document.getElementById("FiscaladdClose");
    fiscalAddClose.addEventListener("click", () => {
        closeModal('FiscalViewQRModal');
    });

    // Close OCC Modal
    const occAddClose = document.getElementById("OCCaddClose");
    occAddClose.addEventListener("click", () => {
        closeModal('OCCViewQRModal');
    });
</script>
