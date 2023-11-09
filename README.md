# Excel Data Exporter using PHP and PhpSpreadsheet

## Description

This project is a PHP application that allows users to export data from a MySQL database into Excel files. It provides an interface for exporting two types of data: user information and deposit records. The exported data can be filtered by various criteria, including status and date range.

The project uses the PhpSpreadsheet library to create and format Excel files, making it easy to customize the appearance and content of the exported data.

## Features

- Export user information, including username, email, and more.
- Export deposit records, including user details, plan name, amount, and creation date.
- Filter data based on status and date range.
- Customizable Excel file naming convention.
- User-friendly web interface for exporting data.

## Installation

1. Clone the repository to your local machine:
   
```git clone https://github.com/your-username/excel-data-exporter.git```

2. Configure your web server (e.g., Apache, Nginx) to serve the project from the repository directory.

3. Set up your MySQL database and configure the database connection in `database.php`.

4. Install the required dependencies using Composer:

5. Open a web browser and access the project using the local or server URL.

## Usage

1. Access the project via the web browser.

2. You will see an interface with options to export user information and deposit records. Select the desired export type.

3. Depending on the export type selected, you will be prompted to specify criteria for filtering the data, such as status and date range.

4. Click the "Export to Excel" button to generate and download the Excel file.

## Configuration

- Configure the database connection in `database.php`.

## Customization

- You can customize the appearance and formatting of the Excel files in the PHP scripts responsible for creating Excel files.

## Contributing

Contributions are welcome! If you'd like to contribute to this project, please follow these guidelines:

1. Fork the repository.

2. Create a new branch for your feature or bug fix.

3. Make your changes and commit them.

4. Push your changes to your fork.

5. Submit a pull request with a clear description of your changes.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Credits

- [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet) - The PHP library used to create Excel files.

## Contact

If you have any questions or issues, please feel free to contact us at info@debuggedagency.com.

## Acknowledgments

We would like to acknowledge the open-source community and the authors of the libraries and tools used in this project.
