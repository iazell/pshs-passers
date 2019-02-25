<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">      
                  <vue-tabs>
                        <v-tab title="Passers Tabs">
                            <div>
                            <br>
                                <h3 class="card-title">Passers List</h3>    
                                <div style="padding-bottom: 20px;">  
                                    <button class="btn btn-success" @click="newModal">Add New Examinee</button>
                                </div>
                                <form @submit.prevent="search()">
                                    <div class="input-group mb-3">
                                        <input v-model="form.search" type="text" class="form-control" placeholder="Search by name, school or division" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                                    </div>
                                    </div>
                                </form>
                            </div>
                            <br>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                        <tbody>
                                            <tr>
                                                <th>Name</th>
                                                <th>Campus Eligibility</th>
                                                <th>School</th>
                                                <th>Division</th>
                                        </tr>

                                        <tr v-for="passer in passers.data" :key="passer.id">
                                            <td>{{passer.name}}</td>
                                            <td>{{passer.camp_eligibility}}</td>
                                            <td>{{passer.school}}</td>
                                            <td>{{passer.division}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <pagination :data="passers" :limit="5" :show-disabled="true" @pagination-change-page="getResults">
                                    <span slot="prev-nav">&lt; Previous</span>
                                    <span slot="next-nav">Next &gt;</span>
                                </pagination>
                            </div>
                            </v-tab>

                        <v-tab title="School Tab">
                            <div>
                                <br>
                                <h3 class="card-title">School List</h3>    
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                        <tbody>
                                            <tr>
                                                <th>School</th>
                                                <th>Number of Passers</th>
                                        </tr>

                                        <tr v-for="passer in schools.data" :key="passer.id">                           
                                            <td>{{passer.school}}</td>
                                            <td>{{passer.count}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                            </div>
                            <!-- /.card-body -->
                            </v-tab>
                        </vue-tabs>            
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewLabel">Add New Examinee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="createPasser()">
                <div class="modal-body">
                     <div class="form-group">
                        <input v-model="form.name" type="text" name="name"
                            placeholder="Name"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                    </div>  
                    <div class="form-group">
                        <input v-model="form.camp_eligibility" type="text" name="camp_eligibility"
                            placeholder="Campus Eligibility"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('camp_eligibility') }">
                        <has-error :form="form" field="camp_eligibility"></has-error>
                    </div>
                    <div class="form-group">
                        <input v-model="form.school" type="text" name="school"
                            placeholder="School"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('school') }">
                        <has-error :form="form" field="school"></has-error>
                    </div>
                        <div class="form-group">
                        <input v-model="form.division" type="text" name="division"
                            placeholder="Division"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('division') }">
                        <has-error :form="form" field="division"></has-error>
                    </div>                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>

                </form>

                </div>
            </div>
            </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
              passers : {},
              schools: {},
              form: new Form({
                id: '',
                name: '',
                camp_eligibility: '',
                school: '',
                division: ''
        })
        }
    },    

    created(){
        this.fetchPassers();
        this.fetchSchool();
    },

    methods: {
        fetchPassers(){
            axios.get("api/passers").then(({ data }) => (this.passers = data));
        },

        fetchSchool(){
            axios.get("api/schools").then(({ data }) => (this.schools = data));
        },

        getResults(page = 1) {
                  axios.get('api/passers?page=' + page)
                      .then(response => {
                          this.passers = response.data;
                      });
        },

        newModal(){
            this.form.reset();
            $('#addNew').modal('show');
        },

        search(){
            axios.get(`api/search/${this.form.search}`)
               .then(response => {
                          this.passers = response.data;
                          console.log(this.passers);
                      });
        },

        createPasser(){
                this.form.post('api/passer')
                .then(()=>{
                    $('#addNew').modal('hide');
                    Vue.swal('Success!', `Examinee ${ this.form.name } is added in the Passers List`, 'success');
                    this.fetchPassers();
                })
                .catch(()=>{
                   $('#addNew').modal('hide');
                    Vue.swal('Oops!', `Cannot add Examinee ${ this.form.name }`, 'error');
                    this.fetchPassers();
                })
                
        },        
    }
}
</script>