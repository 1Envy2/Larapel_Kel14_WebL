@php
// Debug: Check if donor relationship is loaded
$donation = $successfulDonations->first();
if ($donation) {
    error_log('DEBUG - First Donation:');
    error_log('  ID: ' . $donation->id);
    error_log('  Donor ID: ' . $donation->donor_id);
    error_log('  Anonymous: ' . ($donation->anonymous ? 'true' : 'false'));
    error_log('  Donor relation loaded: ' . (isset($donation->donor) ? 'yes' : 'no'));
    error_log('  Donor name: ' . ($donation->donor->name ?? 'NULL'));
    error_log('  Donor_name field: ' . ($donation->donor_name ?? 'NULL'));
}
@endphp
