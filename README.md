# XenForo-ClientDownload

## Description

A simple PHP Script for Cheat Providers that use XenForo as their Forum Software. Allows you to download a randomly named executable based on the user permissions.

## Installation

Clone the repository using

```bash
git clone https://github.com/FelpHooks/XenForo-ClientDownload
```

Or download the script only, by using cURL or Wget and specifying the raw file URL:

```bash
wget <Raw/URL/To/download_client.php>

# or

curl -O <Raw/URL/To/download_client.php>
```

Open the downloaded file in your preferred text editor and modify the constants RETURN_ADDRESS, FILE_NAME, FILE_NAME_LEN and DOWNLOAD_RATE to your preference.

Then move download_client.php to your WebServer directory:

```bash
sudo mv download_client.php /var/www/html/
```

## Usage

On your Download page:

```html
<form action="/download_client.php" method="post">
    <center>
        <button type="submit">Download Client</button>
    </center>
</form>
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License

[GNU GPLv2](https://choosealicense.com/licenses/gpl-2.0/)
