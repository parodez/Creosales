<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Stages</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/sidebar_design.css" />
    <link rel="stylesheet" href="assets/css/header_footer_design.css" />

</head>

<style>
* {
    font-family: 'Mosterrat', sans-serif;
    user-select: none;
}

body {
    background-color: white !important;
}

#main-content {
    margin-top: 55px;
    margin-left: 2.5%;
    margin-right: 2.5%;
    height: 100%;
    text-align: center;
}

#page-title {
    display: inline-block;
    background-color: #4100bf;
    color: white;
    font-weight: bold;
    padding: 0 15px 0 15px;
    border: solid 1px black;
    border-radius: 10px;
}

#tables-container {
    text-align: center;
}

.table-container {
    display: inline-block;
    background-color: white;
    width: 19.8%;
    height: 375px;
    border: solid 1px black;
    border-top: solid 0px black;
    border-radius: 10px;
    overflow-y: auto;

    scrollbar-width: none;
    /* Firefox */
    -ms-overflow-style: none;
    /* IE and Edge */
}

.table-container::-webkit-scrollbar {
    display: none;
    /* Chrome, Safari and Opera */
}

table {
    width: 100%;
    table-layout: fixed;

    thead {
        background-color: #130a2e;
        border-radius: 10px;
        height: 50px !important;
        width: 100%;
        color: white;
        position: sticky;
        top: 0;
        overflow: hidden;

        #col-customer {
            width: 35%;
        }

        #col-stage {
            width: 20%;
        }

        #col-pic {
            width: 35%;
        }

        #col-actions {
            width: 10%;
        }
    }

    th {
        text-align: center;
    }

    tr {
        height: 35px;

    }

    td {
        text-align: center;
        position: relative;
        transition: all 0.1s ease;
    }

    td:hover {
        background-color: #4100bf;
        color: white;
        font-weight: bold;
        cursor: pointer;
        box-shadow: inset 0 0 10px rgba(0, 0, 0, .8);
    }
}

#salesMonitoringTable {
    height: 100%;

    tbody {
        height: 100%;

        tr {
            height: 16.67%;

            td {
                font-size: 24px;
            }
        }
    }
}
</style>

<body>
    <?php require_once __DIR__ . '/partials/header.php' ?>

    <div id="main-content">
        <h1 id="page-title">Customer Stages</h1>

        <div id="tables-container">

            <div class="table-container">
                <table id="profilingTable" data-stage=1 data-link="evaluation_profile.php?id=">
                    <thead>
                        <tr>
                            <th scope="col" class="col-stage">
                                1. Customer Profiling
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="table-container">
                <table id="dataGatheringTable" data-stage=2>
                    <thead>
                        <tr>
                            <th scope="col" class="col-stage">
                                2. Data Gathering
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="table-container">
                <table id="pbaTable" data-stage=3>
                    <thead>
                        <tr>
                            <th scope="col" class="col-stage">
                                3. Potential Business Assessment
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="table-container">
                <table id="pnlTable" data-stage="4">
                    <thead>
                        <tr>
                            <th scope="col" class="col-stage">
                                4. P&L Generation
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="table-container">
                <table id="proposalTable" data-stage="5">
                    <thead>
                        <tr>
                            <th scope="col" class="col-stage">
                                5. Generate Proposal
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="table-container">
                <table id="engagementTable" data-stage="6">
                    <thead>
                        <tr>
                            <th scope="col" class="col-stage">
                                6. Client Engagement
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="table-container">
                <table id="deliveryTable" data-stage=7>
                    <thead>
                        <tr>
                            <th scope="col" class="col-stage">
                                7. Delivery
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="table-container">
                <table id="trainingTable" data-stage=8>
                    <thead>
                        <tr>
                            <th scope="col" class="col-stage">
                                8. Training
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="table-container">
                <table id="monitoringTable" data-stage=9>
                    <thead>
                        <tr>
                            <th scope="col" class="col-stage">
                                9. Monitoring
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/partials/footer.php' ?>

    <script>
    loadTables();


    async function loadTables() {
        let data;
        try {
            const response = await fetch(
                "http://localhost/Creosales/Creosales/new-structure/backend/api/potentialCustomer/");
            if (!response.ok) throw new Error(response.message);
            const result = await response.json();
            data = result.data;
        } catch (error) {
            console.error("Error fetching customers: ", error);
        }

        const tableContainers = document.querySelectorAll('.table-container');

        data.forEach(potentialCustomer => {
            console.log(potentialCustomer);
            console.log(potentialCustomer.potentialcustomer_id);
        });

        tableContainers.forEach(container => {
            const table = container.querySelector('table');
            data.forEach(potentialCustomer => {
                if (potentialCustomer.current_stage == table.dataset.stage) {
                    const row = document.createElement("tr");
                    row.addEventListener("click", () => {
                        window.location.href = table.dataset.link + potentialCustomer
                            .potentialcustomer_id;
                    })
                    row.innerHTML = `
                        <td>${potentialCustomer.potentialcustomer_name}</td>
                    `
                    const tableBody = table.querySelector('tbody');
                    tableBody.appendChild(row);
                }
            });
            // const row = table.createElement("tr");
            console.log(`Found table with id: ${table.id} ${table.dataset.id}`);
        });
    }
    </script>
</body>

</html>