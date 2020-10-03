<template>
    <div>    
        <bootstrap-table :data="data" :options="myOptions" :columns="columns" />
    </div>
</template>

<script>
    import BootstrapTable from 'bootstrap-table/dist/bootstrap-table-vue.min.js'


    export default {
        components: {
            'bootstrap-table': BootstrapTable
        },
        data () {
            return {                
                myOptions: {
                    search: true, 
                    pagination: true,
                    showColumns: true,
                    showPrint: true,
                    showExport: true,
                    filterControl: true,
                    clickToSelect: true,
                    idField: 'id',
                    selectItemName: 'id',                                       
                },
                
                columns: [                    
                    {
                        title: 'Item ID',
                        field: 'id'
                    },
                    {
                        field: 'name',
                        title: 'Item Name'
                    },
                    {
                        field: 'price',
                        title: 'Item Price'
                    },
                    
                ],
                
                data: [
                    {
                        id: 0,
                        name: 'Item 0',
                        price: '$0'
                    },
                    {
                        id: 1,
                        name: 'Item 1',
                        price: '$1'
                    },
                    {
                        id: 2,
                        name: 'Item 2',
                        price: '$2'
                    },
                    {
                        id: 3,
                        name: 'Item 3',
                        price: '$3'
                    },
                    {
                        id: 4,
                        name: 'Item 4',
                        price: '$4'
                    },
                    {
                        id: 5,
                        name: 'Item 5',
                        price: '$5'
                    }
                ],
                    

            }
        },
        
    }
</script>