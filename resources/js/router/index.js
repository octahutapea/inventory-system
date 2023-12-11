import { createRouter, createWebHistory } from 'vue-router'
import { ref } from 'vue';
import DashboardView from '../components/pages/DashboardView.vue'
import LoginView from '../components/pages/LoginView.vue'
import InventoryView from '../components/pages/InventoryView.vue'
import ClaimView from '../components/pages/ClaimView.vue'
import RequestView from '../components/pages/RequestView.vue'
import ClaimProcessView from '../components/pages/ClaimProcessView.vue'
import AcquisitionValueView from '../components/pages/AcquisitionValueView.vue'
import DistributionView from '../components/pages/DistributionView.vue'
import AreaView from '../components/pages/AreaView.vue'
import ContractView from '../components/pages/ContractView.vue'
import DetailContractView from '../components/pages/DetailContractView.vue'
import ContractInvoiceView from '../components/pages/ContractInvoiceView.vue'
import DetailAcquisitionValueView from '../components/pages/DetailAcquisitionValueView.vue'
import DetailItemView from '../components/pages/DetailItemView.vue'
import CompanyView from '../components/pages/CompanyView.vue'
import BudgetView from '../components/pages/BudgetView.vue'
import UserClaimView from '../components/pages/user/UserClaimView.vue'
import UserHistoryView from '../components/pages/user/UserHistoryView.vue'
import UserInventoryView from '../components/pages/user/UserInventoryView.vue'
import ManagerRequestView from '../components/pages/manager/ManagerRequestView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'dashboard',
      component: DashboardView
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/inventory',
      name: 'inventory',
      component: InventoryView
    },
    {
      path: '/claim',
      name: 'claim',
      component: ClaimView
    },
    {
      path: '/request',
      name: 'request',
      component: RequestView
    },
    {
      path: '/claim-process',
      name: 'claim-process',
      component: ClaimProcessView
    },
    {
      path: '/open-document/:filename',
      name: 'open-document',
      component: ClaimView, // Replace with the actual component for rendering documents
    },
    {
      path: '/acquisitionvalue',
      name: 'acquisitionvalue',
      component: AcquisitionValueView
    },
    {
      path: '/distribution',
      name: 'distribution',
      component: DistributionView
    },
    {
      path: '/area/:areaCode',
      name: 'area',
      component: AreaView
    },
    {
      path: '/contract',
      name: 'contract',
      component: ContractView
    },
    {
      path: '/company',
      name: 'company',
      component: CompanyView
    },
    {
      path: '/detailcontract/:companyId',
      name: 'detailcontract',
      component: DetailContractView,
    },
    {
      path: '/detailcontract/invoice/:contractId',
      name: 'contractinvoice',
      component: ContractInvoiceView,
    },
    {
      path: '/detailcontract/:companyId/acquisitionvalue/:contractId',
      name: 'detailacquisitionvalue',
      component: DetailAcquisitionValueView,
    },
    {
      path: '/detailcontract/:companyId/acquisitionvalue/:contractId/item/:acquisitionValueId',
      name: 'detailitem',
      component: DetailItemView,
    },
    {
      path: '/budget',
      name: 'budget',
      component: BudgetView
    },

    {
      path: '/userclaim',
      name: 'userclaim',
      component: UserClaimView
    },
    {
      path: '/userhistory',
      name: 'userhistory',
      component: UserHistoryView
    },
    {
      path: '/userinventory',
      name: 'userinventory',
      component: UserInventoryView
    },
    {
      path: '/managerrequest',
      name: 'managerrequest',
      component: ManagerRequestView
    },
  ]
})

const isAuthenticated = localStorage.getItem('authenticated') === 'true';
const role = localStorage.getItem('role');
router.beforeEach((to, from, next) => { 
  if (to.name !== "login" && !isAuthenticated) next({ name: "login" });
  if (to.name === "login" && isAuthenticated && role === 'Admin') {
    next({ name: "dashboard" });
    return;
  }
  if (to.name === "login" && isAuthenticated && role === 'User') {
    next({ name: "userclaim" });
    return
  }
  if (to.name === "login" && isAuthenticated && role === 'Manager') {
    next({ name: "dashboard" });
    return
  }
  else next();
});

export default router
